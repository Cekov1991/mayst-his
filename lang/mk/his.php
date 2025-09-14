<?php

return [
    // Општо
    'system_name' => 'Болнички Информационен Систем',
    'welcome' => 'Добредојдовте во БИС',
    'dashboard' => 'Контролна табла',
    'search' => 'Барај',
    'add' => 'Додај',
    'edit' => 'Уреди',
    'delete' => 'Избриши',
    'save' => 'Зачувај',
    'cancel' => 'Откажи',
    'actions' => 'Акции',
    'view' => 'Прегледај',
    'back' => 'Назад',
    'next' => 'Следно',
    'previous' => 'Претходно',
    'loading' => 'Се вчитува...',
    'no_records' => 'Нема пронајдени записи.',
    'confirm_delete' => 'Дали сте сигурни дека сакате да го избришете овој запис?',

    // Корисници и улоги
    'users' => [
        'title' => 'Корисници',
        'name' => 'Име',
        'email' => 'Е-пошта',
        'role' => 'Улога',
        'is_active' => 'Активен',
        'created_at' => 'Креиран',
    ],

    'roles' => [
        'admin' => 'Администратор',
        'doctor' => 'Доктор',
        'reception' => 'Рецепција',
        'tech' => 'Техничар',
    ],

    'table_header' => [
        'full_name' => 'Име и презиме',
        'sex' => 'Пол',
        'dob' => 'Датум на раѓање',
        'phone' => 'Телефон',
        'unique_master_citizen_number' => 'Матичен број',
        'actions' => 'Акции',
        'doctor' => 'Доктор',
        'type' => 'Тип',
        'status' => 'Статус',
        'scheduled' => 'Закажано',
        'room' => 'Соба',
        'queue' => 'Ред',
        'actions' => 'Акции',
        'search' => 'Барај',
        'all_statuses' => 'Сите статуси',
        'all_doctors' => 'Сите доктори',
        'date' => 'Датум',
    ],

    'table_cell' => [
        'edit' => 'Уреди',
        'delete' => 'Избриши',
        'view' => 'Преглед',
        'details' => 'Детали',
    ],

    'clear_button' => 'Освежи',
    'search_button' => 'Барај',
    'updating' => 'Ажурирање...',
    'last_updated' => 'Ажурирано',

    // Пациенти
    'patients' => [
        'title' => 'Пациенти',
        'add_patient' => 'Додај пациент',
        'edit_patient' => 'Уреди пациент',
        'patient_details' => 'Детали за пациентот',
        'first_name' => 'Име',
        'last_name' => 'Презиме',
        'full_name' => 'Име и презиме',
        'sex' => 'Пол',
        'dob' => 'Датум на раѓање',
        'age' => 'Возраст',
        'phone' => 'Телефон',
        'email' => 'Е-пошта',
        'address' => 'Адреса',
        'city' => 'Град',
        'country' => 'Држава',
        'unique_master_citizen_number' => 'Матичен број',
        'notes' => 'Забелешки',
        'created_at' => 'Регистриран',
        'visits_count' => 'Вкупно прегледи',
    ],

    'sex_options' => [
        'male' => 'Машки',
        'female' => 'Женски',
        'other' => 'Друго',
        'unknown' => 'Непознато',
    ],

    // Прегледи
    'visits' => [
        'title' => 'Прегледи',
        'add_visit' => 'Додај преглед',
        'edit_visit' => 'Уреди преглед',
        'visit_details' => 'Детали за прегледот',
        'patient' => 'Пациент',
        'doctor' => 'Доктор',
        'type' => 'Тип на преглед',
        'status' => 'Статус',
        'scheduled_at' => 'Закажано за',
        'arrived_at' => 'Пристигнал во',
        'started_at' => 'Почнат во',
        'completed_at' => 'Завршен во',
        'reason_for_visit' => 'Причина за прегледот',
        'room' => 'Соба',
        'queue' => 'Чекаат за преглед',
        'today_queue' => 'Денешен ред',
        'quick_actions' => 'Брзи акцији',
        'manage_visits_and_information' => 'Управувајте со прегледите и информациите за овој пациент.',
        'add_prescription' => 'Додај рецепт',
        'order_imaging' => 'Нарачај имиџинг',
        'add_treatment_plan' => 'Додај план за третман',
        'spectacle_prescription' => 'Препиши очила',
        'messages' => [
            'created_successfully' => 'Прегледот е успешно креиран.',
            'updated_successfully' => 'Прегледот е успешно ажуриран.',
            'deleted_successfully' => 'Прегледот е успешно избришан.',
            'status_updated' => 'Статусот е успешно ажуриран.',
            'delete_failed' => 'Не може да се избрише прегледот. Обидете се повторно.',
        ],
    ],

    'visit_types' => [
        'exam' => 'Преглед',
        'control' => 'Контролен преглед',
        'surgery' => 'Операција',
    ],

    'visit_status' => [
        'scheduled' => 'Закажани',
        'arrived' => 'Пристигнаи',
        'in_progress' => 'Во тек',
        'completed' => 'Завршено',
        'cancelled' => 'Откажано',
        'start' => 'Започни преглед',
        'complete' => 'Заврши преглед',
        'mark_arrived' => 'Означи како пристигнат',
    ],

    // Работен простор за прегледи
    'workspace' => [
        'title' => 'Работен простор за преглед',
        'anamnesis' => 'Анамнеза',
        'examination' => 'Преглед',
        'imaging' => 'Имиџинг',
        'treatment' => 'План за третман',
        'prescriptions' => 'Рецепти',
        'spectacles' => 'Рецепт за очила',
        'summary' => 'Резиме од прегледот',
    ],

    // Анамнеза
    'anamnesis' => [
        'title' => 'Анамнеза',
        'chief_complaint' => 'Главна жалба',
        'history_of_present_illness' => 'Историја на сегашна болест',
        'past_medical_history' => 'Претходна медицинска историја',
        'family_history' => 'Семејна анамнеза',
        'medications_current' => 'Сегашни лекови',
        'allergies' => 'Алергии',
        'therapy_in_use' => 'Тековна терапија',
        'other_notes' => 'Други забелешки',
    ],

    // Офталмолошки преглед
    'exam' => [
        'title' => 'Офталмолошки преглед',
        'visus_od' => 'Визус ДО',
        'visus_os' => 'Визус ЛО',
        'visus' => 'Визус',
        'iop_od' => 'Тонус ДО',
        'iop_os' => 'Тонус ЛО',
        'iop' => 'Очен притисок',
        'anterior_segment_findings_od' => 'Наод на преден сегмент ДО',
        'posterior_segment_findings_od' => 'Наод на заден сегмент ДО',
        'anterior_segment_findings_os' => 'Наод на пресен сегмент ЛО',
        'posterior_segment_findings_os' => 'Наод на заден сегмент ЛО',
        'od' => 'Десно око (ДО)',
        'os' => 'Лево око (ЛО)',
        'ou' => 'Двете очи (ОУ)',
        'visual_acuity_and_iop' => 'Визус и очен притисок',
        'visual_acuity' => 'Визус',
    ],

    // Рефракција
    'refraction' => [
        'title' => 'Рефракција',
        'add_refraction' => 'Додај рефракција',
        'eye' => 'Око',
        'method' => 'Метода',
        'sphere' => 'Сфера',
        'cylinder' => 'Цилиндер',
        'axis' => 'Оска',
        'add_power' => 'Додаток',
        'prism' => 'Призма',
        'base' => 'База',
        'notes' => 'Забелешки',
    ],

    'refraction_methods' => [
        'autorefraction' => 'Авторефракција',
        'lensmeter' => 'Лензметар',
        'subjective' => 'Субјективна',
    ],

    'prism_base' => [
        'up' => 'Горе',
        'down' => 'Долу',
        'in' => 'Внатре',
        'out' => 'Надвор',
    ],

    // Имиџинг студии
    'imaging' => [
        'title' => 'Имиџинг',
        'add_imaging' => 'Додај имиџинг',
        'modality' => 'Модалитет',
        'eye' => 'Око',
        'ordered_by' => 'Нарачано од',
        'performed_by' => 'Изведено од',
        'performed_at' => 'Изведено во',
        'status' => 'Статус',
        'findings' => 'Наоди',
        'attachments' => 'Прилози',
        'studies' => 'Испитувања',
        'order_study' => 'Нарачај испитување',
    ],

    'imaging_modalities' => [
        'OCT' => 'Оптичка кохерентна томографија',
        'VF' => 'Видно поле',
        'US' => 'Ултразвук',
        'FA' => 'Флуоресцеинова ангиографија',
        'Biometry' => 'Биометрија',
        'Photo' => 'Фотографија',
        'Other' => 'Друго',
    ],

    'imaging_eyes' => [
        'OD' => 'Десно око',
        'OS' => 'Лево око',
        'OU' => 'Двете очи',
        'NA' => 'Не се однесува',
    ],

    'imaging_status' => [
        'ordered' => 'Нарачано',
        'done' => 'Завршено',
        'reported' => 'Рапортирано',
    ],

    // Планови за третман
    'treatment' => [
        'title' => 'План за третман',
        'add_treatment' => 'Додај план за третман',
        'plan_type' => 'Тип на план',
        'recommendation' => 'Препорака',
        'details' => 'Детали',
        'planned_date' => 'Планирана дата',
        'status' => 'Статус',
    ],

    'treatment_types' => [
        'surgery' => 'Операција',
        'injection' => 'Инјекција',
        'medical' => 'Медицински третман',
        'advice' => 'Совет',
    ],

    'treatment_status' => [
        'proposed' => 'Предложено',
        'accepted' => 'Прифатено',
        'scheduled' => 'Закажано',
        'done' => 'Завршено',
        'declined' => 'Одбиено',
    ],

    // Рецепти
    'prescriptions' => [
        'title' => 'Рецепти',
        'add_prescription' => 'Додај рецепт',
        'add_item' => 'Додај лек',
        'doctor' => 'Доктор',
        'notes' => 'Забелешки',
        'items' => 'Лекови',
        'drug_name' => 'Име на лекот',
        'form' => 'Форма',
        'strength' => 'Јачина',
        'dosage_instructions' => 'Инструкции за дозирање',
        'duration_days' => 'Времетраење (денови)',
        'repeats' => 'Повторувања',
    ],

    'drug_forms' => [
        'drops' => 'Капки за очи',
        'ointment' => 'Маст',
        'tablet' => 'Таблета',
        'capsule' => 'Капсула',
        'other' => 'Друго',
    ],

    // Рецепти за очила
    'spectacles' => [
        'title' => 'Рецепт за очила',
        'add_prescription' => 'Додај рецепт за очила',
        'doctor' => 'Доктор',
        'od_sphere' => 'ДО Сфера',
        'od_cylinder' => 'ДО Цилиндер',
        'od_axis' => 'ДО Оска',
        'od_add' => 'ДО Додаток',
        'os_sphere' => 'ЛО Сфера',
        'os_cylinder' => 'ЛО Цилиндер',
        'os_axis' => 'ЛО Оска',
        'os_add' => 'ЛО Додаток',
        'pd_distance' => 'ПД далечина',
        'pd_near' => 'ПД близу',
        'type' => 'Тип',
        'notes' => 'Забелешки',
        'valid_until' => 'Важи до',
        'right_eye' => 'Десно око',
        'left_eye' => 'Лево око',
    ],

    'spectacle_types' => [
        'distance' => 'За далечина',
        'near' => 'За близина',
        'bifocal' => 'Бифокални',
        'progressive' => 'Прогресивни',
    ],

    // Навигација и изглед
    'navigation' => [
        'dashboard' => 'Контролна табла',
        'patients' => 'Пациенти',
        'visits' => 'Прегледи',
        'queue' => 'Чекаат за преглед',
        'reports' => 'Извештаи',
        'settings' => 'Поставки',
        'logout' => 'Одјави се',
        'profile' => 'Профил',
    ],

    'doctor_queue' => [
        'title' => 'Денешен ред',
        'manage_daily_patient_queue' => 'Управувајте со денешниот ред на пациенти за прегледи.',
    ],

    // Пораки и известувања
    'messages' => [
        'patient_created' => 'Пациентот е успешно креиран.',
        'patient_updated' => 'Пациентот е успешно ажуриран.',
        'patient_deleted' => 'Пациентот е успешно избришан.',
        'visit_created' => 'Прегледот е успешно креиран.',
        'visit_updated' => 'Прегледот е успешно ажуриран.',
        'visit_started' => 'Прегледот е успешно започнат.',
        'visit_completed' => 'Прегледот е успешно завршен.',
        'anamnesis_saved' => 'Анамнезата е успешно зачувана.',
        'exam_saved' => 'Прегледот е успешно зачуван.',
        'prescription_saved' => 'Рецептот е успешно зачуван.',
        'error_occurred' => 'Се случи грешка. Ве молиме обидете се повторно.',
        'unauthorized' => 'Немате авторизација за оваа акција.',
        'locale_changed' => 'Јазикот е успешно променет.',
        'invalid_locale' => 'Избран е невалиден јазик.',
    ],

    // Дијагнози
    'diagnoses' => [
        'title' => 'Дијагнози',
        'diagnosis' => 'Дијагноза',
        'add_diagnosis' => 'Додај дијагноза',
        'edit_diagnosis' => 'Уреди дијагноза',
        'no_diagnoses' => 'Нема евидентирани дијагнози',
        'add_first_diagnosis' => 'Започнете со креирање дијагноза за оваа визита.',
        'confirm_delete' => 'Дали сте сигурни дека сакате да ја избришете оваа дијагноза?',

        // Полиња
        'term' => 'Дијагностички термин',
        'term_placeholder' => 'Внесете дијагностички термин',
        'eye' => 'Око',
        'select_eye' => 'Избери око',
        'od' => 'Десно око (OD)',
        'os' => 'Лево око (OS)',
        'ou' => 'Двете очи (OU)',
        'na' => 'Не се применува',
        'code' => 'Дијагностички код',
        'code_placeholder' => 'пр., H25.9, E11.3',
        'code_system' => 'Систем на кодови',
        'select_code_system' => 'Избери систем на кодови',
        'local' => 'Локален',
        'status' => 'Статус',
        'provisional' => 'Привремена',
        'working' => 'Работна',
        'confirmed' => 'Потврдена',
        'ruled_out' => 'Исклучена',
        'resolved' => 'Решена',
        'severity' => 'Сериозност',
        'mild' => 'Лесна',
        'moderate' => 'Умерена',
        'severe' => 'Тешка',
        'unknown' => 'Непозната',
        'acuity' => 'Острина',
        'acute' => 'Акутна',
        'subacute' => 'Субакутна',
        'chronic' => 'Хронична',
        'diagnosed_by' => 'Дијагностицирано од',
        'select_doctor' => 'Избери доктор',
        'onset_date' => 'Датум на почеток',
        'is_primary' => 'Примарна дијагноза',
        'primary' => 'Примарна',
        'primary_help' => 'Означете ја како примарна дијагноза за оваа визита.',
        'notes_placeholder' => 'Дополнителни забелешки за оваа дијагноза...',
    ],

    // Работен простор
    'workspace' => [
        'anamnesis' => 'Анамнеза',
        'examination' => 'Офталмолошки преглед',
        'refraction' => 'Рефракција',
        'imaging' => 'Студии за сликање',
        'treatments' => 'Третмани',
        'prescriptions' => 'Рецепти',
        'spectacles' => 'Рецепти за очила',
        'diagnoses' => 'Дијагнози',
    ],

    // Форми и валидација
    'validation' => [
        'required' => 'Ова поле е задолжително.',
        'email' => 'Ве молиме внесете валидна е-пошта.',
        'date' => 'Ве молиме внесете валиден датум.',
        'numeric' => 'Ве молиме внесете валиден број.',
        'min_length' => 'Ова поле мора да има најмалку :min карактери.',
        'max_length' => 'Ова поле не смее да има повеќе од :max карактери.',
    ],
];
