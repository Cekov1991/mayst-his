<?php

return [
    // General
    'system_name' => 'Hospital Information System',
    'welcome' => 'Welcome to HIS',
    'dashboard' => 'Dashboard',
    'search' => 'Search',
    'add' => 'Add',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'save' => 'Save',
    'cancel' => 'Cancel',
    'actions' => 'Actions',
    'view' => 'View',
    'back' => 'Back',
    'next' => 'Next',
    'previous' => 'Previous',
    'loading' => 'Loading...',
    'no_records' => 'No records found.',
    'confirm_delete' => 'Are you sure you want to delete this record?',

    // Users & Roles
    'users' => [
        'title' => 'Users',
        'name' => 'Name',
        'email' => 'Email',
        'role' => 'Role',
        'is_active' => 'Active',
        'created_at' => 'Created At',
    ],

    'roles' => [
        'admin' => 'Administrator',
        'doctor' => 'Doctor',
        'reception' => 'Receptionist',
        'tech' => 'Technician',
    ],

    'table_header' => [
        'full_name' => 'Full Name',
        'sex' => 'Sex',
        'dob' => 'Date of Birth',
        'phone' => 'Phone',
        'unique_master_citizen_number' => 'Citizen Number',
        'actions' => 'Actions',
        'doctor' => 'Doctor',
        'type' => 'Type',
        'status' => 'Status',
        'scheduled' => 'Scheduled',
        'room' => 'Room',
        'search' => 'Search',
        'all_statuses' => 'All Statuses',
        'all_doctors' => 'All Doctors',
        'date' => 'Date',
        'details' => 'Details',
    ],

    'table_cell' => [
        'edit' => 'Edit',
        'delete' => 'Delete',
        'view' => 'View',
        'details' => 'Details',
    ],

    'clear_button' => 'Clear',
    'search_button' => 'Search',
    'updating' => 'Updating...',
    'last_updated' => 'Last Updated',

    // Patients
    'patients' => [
        'title' => 'Patients',
        'add_patient' => 'Add Patient',
        'edit_patient' => 'Edit Patient',
        'patient_details' => 'Patient Details',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'full_name' => 'Full Name',
        'sex' => 'Sex',
        'dob' => 'Date of Birth',
        'age' => 'Age',
        'phone' => 'Phone',
        'email' => 'Email',
        'address' => 'Address',
        'city' => 'City',
        'country' => 'Country',
        'unique_master_citizen_number' => 'Citizen Number',
        'notes' => 'Notes',
        'created_at' => 'Registered',
        'visits_count' => 'Total Visits',
    ],

    'sex_options' => [
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other',
        'unknown' => 'Unknown',
    ],

    // Visits
    'visits' => [
        'title' => 'Visits',
        'add_visit' => 'Add Visit',
        'edit_visit' => 'Edit Visit',
        'visit_details' => 'Visit Details',
        'patient' => 'Patient',
        'doctor' => 'Doctor',
        'type' => 'Visit Type',
        'status' => 'Status',
        'scheduled_at' => 'Scheduled At',
        'arrived_at' => 'Arrived At',
        'started_at' => 'Started At',
        'completed_at' => 'Completed At',
        'reason_for_visit' => 'Reason for Visit',
        'room' => 'Room',
        'queue' => 'Doctor Queue',
        'today_queue' => 'Today\'s Queue',
        'quick_actions' => 'Quick Actions',
        'manage_visits_and_information' => 'Manage this patient\'s visits and information.',
        'add_prescription' => 'Add Prescription',
        'order_imaging' => 'Order Imaging',
        'add_treatment_plan' => 'Add Treatment Plan',
        'spectacle_prescription' => 'Spectacle Prescription',
        'messages' => [
            'created_successfully' => 'Visit created successfully.',
            'updated_successfully' => 'Visit updated successfully.',
            'deleted_successfully' => 'Visit deleted successfully.',
            'status_updated' => 'Status updated successfully.',
            'delete_failed' => 'Unable to delete visit. Please try again.',
        ],
    ],

    'visit_types' => [
        'exam' => 'Examination',
        'control' => 'Control Visit',
        'surgery' => 'Surgery',
    ],

    'visit_status' => [
        'scheduled' => 'Scheduled',
        'arrived' => 'Arrived',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'start' => 'Start Visit',
        'complete' => 'Complete Visit',
        'mark_arrived' => 'Mark as Arrived',
    ],

    // Visit Workspace Tabs
    'workspace' => [
        'title' => 'Visit Workspace',
        'anamnesis' => 'Anamnesis',
        'examination' => 'Examination',
        'imaging' => 'Imaging',
        'treatment' => 'Treatment Plan',
        'prescriptions' => 'Prescriptions',
        'spectacles' => 'Spectacle Prescription',
        'summary' => 'Visit Summary',
    ],

    // Anamnesis
    'anamnesis' => [
        'title' => 'Anamnesis',
        'chief_complaint' => 'Chief Complaint',
        'history_of_present_illness' => 'History of Present Illness',
        'past_medical_history' => 'Past Medical History',
        'family_history' => 'Family History',
        'medications_current' => 'Current Medications',
        'allergies' => 'Allergies',
        'therapy_in_use' => 'Current Therapy',
        'other_notes' => 'Other Notes',
    ],

    // Ophthalmic Examination
    'exam' => [
        'title' => 'Ophthalmic Examination',
        'visus_od' => 'Visual Acuity OD',
        'visus_os' => 'Visual Acuity OS',
        'visus' => 'Visual Acuity',
        'iop_od' => 'IOP OD',
        'iop_os' => 'IOP OS',
        'iop' => 'Intraocular Pressure',
        'anterior_segment_findings_od' => 'Anterior Segment Findings OD',
        'posterior_segment_findings_od' => 'Posterior Segment Findings OD',
        'anterior_segment_findings_os' => 'Anterior Segment Findings OS',
        'posterior_segment_findings_os' => 'Posterior Segment Findings OS',
        'od' => 'Right Eye (OD)',
        'os' => 'Left Eye (OS)',
        'ou' => 'Both Eyes (OU)',
        'visual_acuity_and_iop' => 'Visual Acuty and IOP',
    ],

    // Refraction
    'refraction' => [
        'title' => 'Refraction',
        'add_refraction' => 'Add Refraction',
        'eye' => 'Eye',
        'method' => 'Method',
        'sphere' => 'Sphere',
        'cylinder' => 'Cylinder',
        'axis' => 'Axis',
        'add_power' => 'Add Power',
        'prism' => 'Prism',
        'base' => 'Base',
        'notes' => 'Notes',
    ],

    // Additional workspace keys
    'drug_forms' => [
        'drops' => 'Eye Drops',
        'ointment' => 'Ointment',
        'tablet' => 'Tablet',
        'capsule' => 'Capsule',
        'other' => 'Other',
    ],

    'refraction_methods' => [
        'autorefraction' => 'Autorefraction',
        'lensmeter' => 'Lensmeter',
        'subjective' => 'Subjective',
    ],

    'spectacle_types' => [
        'distance' => 'Distance',
        'near' => 'Near',
        'bifocal' => 'Bifocal',
        'progressive' => 'Progressive',
    ],

    // Additional general keys
    'save' => 'Save',
    'update' => 'Update',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'back' => 'Back',
    'close' => 'Close',
    'cancel' => 'Cancel',

    // Imaging Studies
    'imaging' => [
        'title' => 'Imaging Studies',
        'add_imaging' => 'Add Imaging Study',
        'modality' => 'Modality',
        'eye' => 'Eye',
        'ordered_by' => 'Ordered By',
        'performed_by' => 'Performed By',
        'performed_at' => 'Performed At',
        'status' => 'Status',
        'findings' => 'Findings',
        'attachments' => 'Attachments',
    ],

    'imaging_modalities' => [
        'OCT' => 'Optical Coherence Tomography',
        'VF' => 'Visual Field',
        'US' => 'Ultrasound',
        'FA' => 'Fluorescein Angiography',
        'Biometry' => 'Biometry',
        'Photo' => 'Photography',
        'Other' => 'Other',
    ],

    'imaging_eyes' => [
        'OD' => 'Right Eye',
        'OS' => 'Left Eye',
        'OU' => 'Both Eyes',
        'NA' => 'Not Applicable',
    ],

    'imaging_status' => [
        'ordered' => 'Ordered',
        'done' => 'Done',
        'reported' => 'Reported',
    ],

    // Treatment Plans
    'treatment' => [
        'title' => 'Treatment Plan',
        'add_treatment' => 'Add Treatment Plan',
        'plan_type' => 'Plan Type',
        'recommendation' => 'Recommendation',
        'details' => 'Details',
        'planned_date' => 'Planned Date',
        'status' => 'Status',
    ],

    'treatment_types' => [
        'surgery' => 'Surgery',
        'injection' => 'Injection',
        'medical' => 'Medical Treatment',
        'advice' => 'Advice',
    ],

    'treatment_status' => [
        'proposed' => 'Proposed',
        'accepted' => 'Accepted',
        'scheduled' => 'Scheduled',
        'done' => 'Done',
        'declined' => 'Declined',
    ],

    // Prescriptions
    'prescriptions' => [
        'title' => 'Prescriptions',
        'add_prescription' => 'Add Prescription',
        'add_item' => 'Add Medication',
        'doctor' => 'Doctor',
        'notes' => 'Notes',
        'items' => 'Medications',
        'drug_name' => 'Drug Name',
        'form' => 'Form',
        'strength' => 'Strength',
        'dosage_instructions' => 'Dosage Instructions',
        'duration_days' => 'Duration (Days)',
        'repeats' => 'Repeats',
    ],

    // Spectacle Prescriptions
    'spectacles' => [
        'title' => 'Spectacle Prescription',
        'add_prescription' => 'Add Spectacle Prescription',
        'doctor' => 'Doctor',
        'od_sphere' => 'OD Sphere',
        'od_cylinder' => 'OD Cylinder',
        'od_axis' => 'OD Axis',
        'od_add' => 'OD Add',
        'os_sphere' => 'OS Sphere',
        'os_cylinder' => 'OS Cylinder',
        'os_axis' => 'OS Axis',
        'os_add' => 'OS Add',
        'pd_distance' => 'PD Distance',
        'pd_near' => 'PD Near',
        'type' => 'Type',
        'notes' => 'Notes',
        'valid_until' => 'Valid Until',
        'right_eye' => 'Right Eye',
        'left_eye' => 'Left Eye',
    ],

    // Navigation & Layout
    'navigation' => [
        'dashboard' => 'Dashboard',
        'patients' => 'Patients',
        'visits' => 'Visits',
        'queue' => 'Doctor Queue',
        'reports' => 'Reports',
        'settings' => 'Settings',
        'logout' => 'Logout',
        'profile' => 'Profile',
    ],

    'doctor_queue' => [
        'title' => 'Doctor Queue',
        'manage_daily_patient_queue' => 'Manage your daily patient queue and visit statuses in real-time.',
    ],

    // Messages & Notifications
    'messages' => [
        'patient_created' => 'Patient created successfully.',
        'patient_updated' => 'Patient updated successfully.',
        'patient_deleted' => 'Patient deleted successfully.',
        'visit_created' => 'Visit created successfully.',
        'visit_updated' => 'Visit updated successfully.',
        'visit_deleted' => 'Visit deleted successfully.',
        'visit_started' => 'Visit started successfully.',
        'visit_completed' => 'Visit completed successfully.',
        'status_updated' => 'Status updated successfully.',
        'delete_failed' => 'Unable to delete. Please try again.',
        'anamnesis_saved' => 'Anamnesis saved successfully.',
        'exam_saved' => 'Examination saved successfully.',
        'prescription_saved' => 'Prescription saved successfully.',
        'error_occurred' => 'An error occurred. Please try again.',
        'unauthorized' => 'You are not authorized to perform this action.',
        'locale_changed' => 'Language changed successfully.',
        'invalid_locale' => 'Invalid language selected.',
    ],

    // Diagnoses
    'diagnoses' => [
        'title' => 'Diagnoses',
        'diagnosis' => 'Diagnosis',
        'add_diagnosis' => 'Add Diagnosis',
        'edit_diagnosis' => 'Edit Diagnosis',
        'no_diagnoses' => 'No diagnoses recorded',
        'add_first_diagnosis' => 'Get started by creating a diagnosis for this visit.',
        'confirm_delete' => 'Are you sure you want to delete this diagnosis?',

        // Fields
        'term' => 'Diagnosis Term',
        'term_placeholder' => 'Enter diagnosis term',
        'eye' => 'Eye',
        'select_eye' => 'Select Eye',
        'od' => 'Right Eye (OD)',
        'os' => 'Left Eye (OS)',
        'ou' => 'Both Eyes (OU)',
        'na' => 'Not Applicable',
        'code' => 'Diagnosis Code',
        'code_placeholder' => 'e.g., H25.9, E11.3',
        'code_system' => 'Code System',
        'select_code_system' => 'Select Code System',
        'local' => 'Local',
        'status' => 'Status',
        'provisional' => 'Provisional',
        'working' => 'Working',
        'confirmed' => 'Confirmed',
        'ruled_out' => 'Ruled Out',
        'resolved' => 'Resolved',
        'severity' => 'Severity',
        'mild' => 'Mild',
        'moderate' => 'Moderate',
        'severe' => 'Severe',
        'unknown' => 'Unknown',
        'acuity' => 'Acuity',
        'acute' => 'Acute',
        'subacute' => 'Subacute',
        'chronic' => 'Chronic',
        'diagnosed_by' => 'Diagnosed By',
        'select_doctor' => 'Select Doctor',
        'onset_date' => 'Onset Date',
        'is_primary' => 'Primary Diagnosis',
        'primary' => 'Primary',
        'primary_help' => 'Mark this as the primary diagnosis for this visit.',
        'notes_placeholder' => 'Additional notes about this diagnosis...',
    ],

    // Workspace
    'workspace' => [
        'anamnesis' => 'Anamnesis',
        'examination' => 'Ophthalmic Examination',
        'refraction' => 'Refraction',
        'imaging' => 'Imaging Studies',
        'treatments' => 'Treatments',
        'prescriptions' => 'Prescriptions',
        'spectacles' => 'Spectacle Prescriptions',
        'diagnoses' => 'Diagnoses',
    ],

    // Forms & Validation
    'validation' => [
        'required' => 'This field is required.',
        'email' => 'Please enter a valid email address.',
        'date' => 'Please enter a valid date.',
        'numeric' => 'Please enter a valid number.',
        'min_length' => 'This field must be at least :min characters.',
        'max_length' => 'This field must not exceed :max characters.',
    ],
];
