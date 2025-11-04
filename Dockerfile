FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Node.js (LTS version)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install Chrome dependencies for Puppeteer
RUN apt-get update && apt-get install -y \
    wget \
    gnupg \
    ca-certificates \
    fonts-liberation \
    libasound2 \
    libatk-bridge2.0-0 \
    libatk1.0-0 \
    libatspi2.0-0 \
    libcups2 \
    libdbus-1-3 \
    libdrm2 \
    libgbm1 \
    libgtk-3-0 \
    libnspr4 \
    libnss3 \
    libwayland-client0 \
    libxcomposite1 \
    libxdamage1 \
    libxfixes3 \
    libxkbcommon0 \
    libxrandr2 \
    xdg-utils \
    && rm -rf /var/lib/apt/lists/*

# Install Chrome for Puppeteer (architecture-aware)
# For ARM architectures, use Chromium from repos; for amd64, use Google Chrome
RUN ARCH=$(dpkg --print-architecture) \
    && mkdir -p /usr/share/keyrings \
    && if [ "$ARCH" = "amd64" ]; then \
        wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | gpg --dearmor -o /usr/share/keyrings/google-chrome-keyring.gpg \
        && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/google-chrome-keyring.gpg] http://dl.google.com/linux/chrome/deb/ stable main" > /etc/apt/sources.list.d/google-chrome.list \
        && apt-get update \
        && apt-get install -y google-chrome-stable; \
    else \
        apt-get update \
        && apt-get install -y chromium chromium-driver; \
    fi \
    && rm -rf /var/lib/apt/lists/*

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-scripts --no-autoloader

# Copy package files
COPY package.json package-lock.json* ./

# Install Node dependencies
RUN npm ci

# Install Chrome/Chromium for Puppeteer
# Install as root first, then copy to www-data's cache directory with proper permissions
RUN mkdir -p /var/www/.cache/puppeteer \
    && npx puppeteer browsers install chrome || npx puppeteer browsers install chrome-headless-shell \
    && cp -r /root/.cache/puppeteer/* /var/www/.cache/puppeteer/ 2>/dev/null || true \
    && chown -R www-data:www-data /var/www/.cache/puppeteer

# Copy application code
COPY . .

# Create necessary directories and set permissions before composer scripts
RUN mkdir -p bootstrap/cache storage/framework/cache storage/framework/sessions storage/framework/views storage/logs \
    && chmod -R 775 bootstrap/cache storage

# Complete composer installation
RUN composer dump-autoload --optimize

# Build assets
RUN npm run build

# Set final permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache \
    && mkdir -p /var/www/.cache/puppeteer \
    && chown -R www-data:www-data /var/www/.cache \
    && chmod +x /var/www/html/docker/chromium-wrapper.sh 2>/dev/null || true \
    && chmod +x /var/www/html/docker/fake-crashpad-handler.sh 2>/dev/null || true

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]

