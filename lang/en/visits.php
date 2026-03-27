<?php

return [
    'title' => 'Visits',
    'subtitle' => 'Manage patient visits, appointments, and schedules in the system.',
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
    'copy_from_previous_visit' => 'Copy from Previous Visit',
    'copy_instruction' => 'Select Data to Copy',
    'copy_description' => 'Choose the medical information you want to copy from the previous visit. Selected data will be added to the current visit.',
    'copy_selected_data' => 'Copy Selected Data',
    'medical_history' => 'Medical History',
    'examination_data' => 'Examination Data',
    'refractions' => 'Refractions',
    'diagnoses' => 'Diagnoses',
    'prescriptions' => 'Prescriptions',
    'spectacle_prescriptions' => 'Spectacle Prescriptions',
    'treatment_plans' => 'Treatment Plans',

    // Visit Types
    'types' => [
        'exam' => 'Examination',
        'control' => 'Control Visit',
        'surgery' => 'Surgery',
    ],

    // Visit Status
    'statuses' => [
        'scheduled' => 'Scheduled',
        'arrived' => 'Arrived',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'start' => 'Start Visit',
        'complete' => 'Complete Visit',
        'mark_arrived' => 'Mark as Arrived',
    ],

    // Messages
    'messages' => [
        'created_successfully' => 'Visit created successfully.',
        'updated_successfully' => 'Visit updated successfully.',
        'deleted_successfully' => 'Visit deleted successfully.',
        'status_updated' => 'Status updated successfully.',
        'delete_failed' => 'Unable to delete visit. Please try again.',
        'copied_successfully' => 'Successfully copied: :items',
    ],
];

