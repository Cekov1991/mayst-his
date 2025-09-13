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

    // Пациенти
    'patients' => [
        'title' => 'Пациенти',
        'add_patient' => 'Додај пациент',
        'edit_patient' => 'Уреди пациент',
        'patient_details' => 'Детали за пациентот',
        'first_name' => 'Име',
        'last_name' => 'Презиме',
        'full_name' => 'Полно име',
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
        'queue' => 'Ред за прегледи',
        'today_queue' => 'Денешен ред',
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
        'scheduled' => 'Закажано',
        'arrived' => 'Пристигнал',
        'in_progress' => 'Во тек',
        'completed' => 'Завршено',
        'cancelled' => 'Откажано',
        'start' => 'Почни преглед',
        'complete' => 'Заврши преглед',
        'mark_arrived' => 'Означи како пристигнал',
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
        'anterior_segment_findings' => 'Наод на преден сегмент',
        'posterior_segment_findings' => 'Наод на заден сегмент',
        'od' => 'Десно око (ДО)',
        'os' => 'Лево око (ЛО)',
        'ou' => 'Двете очи (ОУ)',
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
        'title' => 'Имиџинг студии',
        'add_imaging' => 'Додај имиџинг студија',
        'modality' => 'Модалитет',
        'eye' => 'Око',
        'ordered_by' => 'Нарачано од',
        'performed_by' => 'Изведено од',
        'performed_at' => 'Изведено во',
        'status' => 'Статус',
        'findings' => 'Наоди',
        'attachments' => 'Прилози',
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
        'add_spectacles' => 'Додај рецепт за очила',
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
        'queue' => 'Ред за прегледи',
        'reports' => 'Извештаи',
        'settings' => 'Поставки',
        'logout' => 'Одјави се',
        'profile' => 'Профил',
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
