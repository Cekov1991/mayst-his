<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('pdf.visit_report') }} - {{ $visit->patient->full_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 10px 20px;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.6;
            color: #1f2937;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #4f46e5;
        }

        .header h1 {
            font-size: 20pt;
            color: #4f46e5;
            margin-bottom: 5px;
        }

        .header .subtitle {
            font-size: 8pt;
            color: #6b7280;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
        }

        .grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .grid-row {
            display: table-row;
        }

        .grid-col {
            display: table-cell;
            padding: 5px 10px 5px 0;
            vertical-align: top;
        }

        .grid-col-2 {
            width: 50%;
        }

        .grid-col-3 {
            width: 33.333%;
        }

        .label {
            font-weight: bold;
            color: #374151;
            font-size: 8pt;
        }

        .value {
            color: #1f2937;
            margin-top: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            border: 1px solid #d1d5db;
            font-size: 8pt;
        }

        table td {
            padding: 6px 8px;
            border: 1px solid #d1d5db;
            font-size: 8pt;
        }

        table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .empty-state {
            color: #9ca3af;
            font-style: italic;
            padding: 10px;
            background-color: #f9fafb;
            border-radius: 4px;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 9pt;
            font-weight: 600;
        }

        .badge-primary {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            font-size: 9pt;
            color: #6b7280;
        }

        .signature-area {
            margin-top: 50px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 10px;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
        }

        .text-content {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ __('pdf.visit_report') }}</h1>
        <div class="subtitle">{{ __('pdf.generated_on') }}: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <!-- Patient Demographics -->
    <div class="section">
        <h2 class="section-title">{{ __('pdf.patient_information') }}</h2>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-col grid-col-2">
                    <div class="label">{{ __('patients.full_name') }}</div>
                    <div class="value">{{ $visit->patient->full_name }}</div>
                </div>
                <div class="grid-col grid-col-2">
                    <div class="label">{{ __('patients.dob') }}</div>
                    <div class="value">{{ $visit->patient->dob?->format('d/m/Y') ?? '—' }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-col grid-col-2">
                    <div class="label">{{ __('patients.sex') }}</div>
                    <div class="value">{{ __("common.sex_options.{$visit->patient->sex}") }}</div>
                </div>
                <div class="grid-col grid-col-2">
                    <div class="label">{{ __('patients.unique_master_citizen_number') }}</div>
                    <div class="value">{{ $visit->patient->unique_master_citizen_number ?? '—' }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-col grid-col-2">
                    <div class="label">{{ __('patients.phone') }}</div>
                    <div class="value">{{ $visit->patient->phone ?? '—' }}</div>
                </div>
                <div class="grid-col grid-col-2">
                    <div class="label">{{ __('patients.email') }}</div>
                    <div class="value">{{ $visit->patient->email ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visit Information -->
    <div class="section">
        <h2 class="section-title">{{ __('pdf.visit_information') }}</h2>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-col grid-col-3">
                    <div class="label">{{ __('visits.scheduled_at') }}</div>
                    <div class="value">{{ $visit->scheduled_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="grid-col grid-col-3">
                    <div class="label">{{ __('visits.type') }}</div>
                    <div class="value">{{ __("visits.types.{$visit->type}") }}</div>
                </div>
                <div class="grid-col grid-col-3">
                    <div class="label">{{ __('visits.status') }}</div>
                    <div class="value">{{ __("visits.statuses.{$visit->status}") }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-col grid-col-2">
                    <div class="label">{{ __('visits.doctor') }}</div>
                    <div class="value">{{ $visit->doctor->name }}</div>
                </div>
                <div class="grid-col grid-col-2">
                    <div class="label">{{ __('visits.reason_for_visit') }}</div>
                    <div class="value">{{ $visit->reason_for_visit ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Anamnesis -->
    <div class="section">
        <h2 class="section-title">{{ __('workspace.anamnesis') }}</h2>
        @if($visit->anamnesis)
            <div class="grid">
                @if($visit->anamnesis->chief_complaint)
                    <div class="grid-row">
                        <div class="grid-col">
                            <div class="label">{{ __('anamnesis.chief_complaint') }}</div>
                            <div class="value text-content">{{ $visit->anamnesis->chief_complaint }}</div>
                        </div>
                    </div>
                @endif

                @if($visit->anamnesis->history_of_present_illness)
                    <div class="grid-row">
                        <div class="grid-col">
                            <div class="label">{{ __('anamnesis.history_of_present_illness') }}</div>
                            <div class="value text-content">{{ $visit->anamnesis->history_of_present_illness }}</div>
                        </div>
                    </div>
                @endif

                @if($visit->anamnesis->past_medical_history)
                    <div class="grid-row">
                        <div class="grid-col">
                            <div class="label">{{ __('anamnesis.past_medical_history') }}</div>
                            <div class="value text-content">{{ $visit->anamnesis->past_medical_history }}</div>
                        </div>
                    </div>
                @endif

                @if($visit->anamnesis->family_history)
                    <div class="grid-row">
                        <div class="grid-col">
                            <div class="label">{{ __('anamnesis.family_history') }}</div>
                            <div class="value text-content">{{ $visit->anamnesis->family_history }}</div>
                        </div>
                    </div>
                @endif

                @if($visit->anamnesis->medications_current)
                    <div class="grid-row">
                        <div class="grid-col">
                            <div class="label">{{ __('anamnesis.medications_current') }}</div>
                            <div class="value text-content">{{ $visit->anamnesis->medications_current }}</div>
                        </div>
                    </div>
                @endif

                @if($visit->anamnesis->allergies)
                    <div class="grid-row">
                        <div class="grid-col">
                            <div class="label">{{ __('anamnesis.allergies') }}</div>
                            <div class="value text-content">{{ $visit->anamnesis->allergies }}</div>
                        </div>
                    </div>
                @endif

                @if($visit->anamnesis->therapy_in_use)
                    <div class="grid-row">
                        <div class="grid-col">
                            <div class="label">{{ __('anamnesis.therapy_in_use') }}</div>
                            <div class="value text-content">{{ $visit->anamnesis->therapy_in_use }}</div>
                        </div>
                    </div>
                @endif

                @if($visit->anamnesis->other_notes)
                    <div class="grid-row">
                        <div class="grid-col">
                            <div class="label">{{ __('anamnesis.other_notes') }}</div>
                            <div class="value text-content">{{ $visit->anamnesis->other_notes }}</div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="empty-state">{{ __('pdf.no_anamnesis_recorded') }}</div>
        @endif
    </div>

    <!-- Ophthalmic Examination -->
    <div class="section">
        <h2 class="section-title">{{ __('workspace.examination') }}</h2>
        @if($visit->ophthalmicExam)
            <div class="grid">
                <div class="grid-row">
                    <div class="grid-col grid-col-2">
                        <div class="label">{{ __('examination.visus_od') }}</div>
                        <div class="value">{{ $visit->ophthalmicExam->visus_od ?? '—' }}</div>
                    </div>
                    <div class="grid-col grid-col-2">
                        <div class="label">{{ __('examination.visus_os') }}</div>
                        <div class="value">{{ $visit->ophthalmicExam->visus_os ?? '—' }}</div>
                    </div>
                </div>
                <div class="grid-row">
                    <div class="grid-col grid-col-2">
                        <div class="label">{{ __('examination.iop_od') }}</div>
                        <div class="value">{{ $visit->ophthalmicExam->iop_od ?? '—' }}</div>
                    </div>
                    <div class="grid-col grid-col-2">
                        <div class="label">{{ __('examination.iop_os') }}</div>
                        <div class="value">{{ $visit->ophthalmicExam->iop_os ?? '—' }}</div>
                    </div>
                </div>
                <div class="grid-row">
                    @if($visit->ophthalmicExam->anterior_segment_findings_od)
                        <div class="grid-col grid-col-2">
                            <div class="label">{{ __('examination.anterior_segment_findings_od') }}</div>
                            <div class="value text-content">{{ $visit->ophthalmicExam->anterior_segment_findings_od }}</div>
                        </div>
                    @endif

                    @if($visit->ophthalmicExam->anterior_segment_findings_os)
                        <div class="grid-col grid-col-2">
                            <div class="label">{{ __('examination.anterior_segment_findings_os') }}</div>
                            <div class="value text-content">{{ $visit->ophthalmicExam->anterior_segment_findings_os }}</div>
                        </div>
                    @endif
                </div>

                <div class="grid-row">
                    @if($visit->ophthalmicExam->posterior_segment_findings_od)
                        <div class="grid-col grid-col-2">
                            <div class="label">{{ __('examination.posterior_segment_findings_od') }}</div>
                            <div class="value text-content">{{ $visit->ophthalmicExam->posterior_segment_findings_od }}</div>
                        </div>
                    @endif

                    @if($visit->ophthalmicExam->posterior_segment_findings_os)
                        <div class="grid-col grid-col-2">
                            <div class="label">{{ __('examination.posterior_segment_findings_os') }}</div>
                            <div class="value text-content">{{ $visit->ophthalmicExam->posterior_segment_findings_os }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Refraction Data -->
            @if($visit->ophthalmicExam->refractions->isNotEmpty())
                <h3 style="margin-top: 15px; margin-bottom: 10px; font-size: 12pt;">{{ __('refraction.title') }}</h3>
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('refraction.eye') }}</th>
                            <th>{{ __('refraction.method') }}</th>
                            <th>{{ __('refraction.sphere') }}</th>
                            <th>{{ __('refraction.cylinder') }}</th>
                            <th>{{ __('refraction.axis') }}</th>
                            <th>{{ __('refraction.add_power') }}</th>
                            <th>{{ __('refraction.notes') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visit->ophthalmicExam->refractions as $refraction)
                            <tr>
                                <td>{{ __("examination.{$refraction->eye}") }}</td>
                                <td>{{ $refraction->method ?? '—' }}</td>
                                <td>{{ $refraction->sphere ?? '—' }}</td>
                                <td>{{ $refraction->cylinder ?? '—' }}</td>
                                <td>{{ $refraction->axis ?? '—' }}</td>
                                <td>{{ $refraction->add_power ?? '—' }}</td>
                                <td>{{ $refraction->notes ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @else
            <div class="empty-state">{{ __('pdf.no_examination_recorded') }}</div>
        @endif
    </div>

    <!-- Diagnoses -->
    <div class="section">
        <h2 class="section-title">{{ __('workspace.diagnoses') }}</h2>
        @if($visit->diagnoses->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>{{ __('diagnoses.code') }}</th>
                        <th>{{ __('diagnoses.term') }}</th>
                        <th>{{ __('diagnoses.onset_date') }}</th>
                        <th>{{ __('diagnoses.is_primary') }}</th>
                        <th>{{ __('diagnoses.notes_placeholder') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visit->diagnoses as $diagnosis)
                        <tr>
                            <td>{{ $diagnosis->code ?? '—' }}</td>
                            <td>{{ $diagnosis->term }}</td>
                            <td>{{ $diagnosis->onset_date?->format('d/m/Y') ?? '—' }}</td>
                            <td>{{ $diagnosis->is_primary ? __('yes') : __('no') }}</td>
                            <td>{{ $diagnosis->notes ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">{{ __('pdf.no_diagnoses_recorded') }}</div>
        @endif
    </div>

    <!-- Imaging Studies -->
    <div class="section">
        <h2 class="section-title">{{ __('workspace.imaging') }}</h2>
        @if($visit->imagingStudies->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>{{ __('imaging.modality') }}</th>
                        <th>{{ __('imaging.eye') }}</th>
                        <th>{{ __('imaging.status') }}</th>
                        <th>{{ __('imaging.findings') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visit->imagingStudies as $imaging)
                        <tr>
                            <td>{{ __("imaging.modalities.{$imaging->modality}") }}</td>
                            <td>{{ __("imaging.eyes.{$imaging->eye}") }}</td>
                            <td>{{ __("imaging.statuses.{$imaging->status}") }}</td>
                            <td>{{ $imaging->findings ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">{{ __('pdf.no_imaging_recorded') }}</div>
        @endif
    </div>

    <!-- Treatment Plans -->
    <div class="section">
        <h2 class="section-title">{{ __('workspace.treatments') }}</h2>
        @if($visit->treatmentPlans->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>{{ __('treatments.plan_type') }}</th>
                        <th>{{ __('treatments.recommendation') }}</th>
                        <th>{{ __('treatments.details') }}</th>
                        <th>{{ __('treatments.planned_date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visit->treatmentPlans as $treatment)
                        <tr>
                            <td>{{ $treatment->plan_type }}</td>
                            <td>{{ $treatment->recommendation }}</td>
                            <td>{{ $treatment->details ?? '—' }}</td>
                            <td>{{ $treatment->start_date?->format('d/m/Y') ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">{{ __('pdf.no_treatments_recorded') }}</div>
        @endif
    </div>

    <!-- Prescriptions -->
    <div class="section">
        <h2 class="section-title">{{ __('workspace.prescriptions') }}</h2>
        @if($visit->prescriptions->isNotEmpty())
            @foreach($visit->prescriptions as $prescription)
                <div style="margin-bottom: 15px;">
                    <strong> #{{ $loop->iteration }}</strong>
                    @if($prescription->notes)
                        <div style="margin-top: 5px; font-style: italic; color: #6b7280;">{{ $prescription->notes }}</div>
                    @endif
                    <table style="margin-top: 10px;">
                        <thead>
                            <tr>
                                <th>{{ __('prescriptions.medication_name') }}</th>
                                <th>{{ __('prescriptions.dosage') }}</th>
                                <th>{{ __('prescriptions.frequency') }}</th>
                                <th>{{ __('prescriptions.duration') }}</th>
                                <th>{{ __('prescriptions.instructions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescription->prescriptionItems as $item)
                                <tr>
                                    <td>{{ $item->medication_name }}</td>
                                    <td>{{ $item->dosage ?? '—' }}</td>
                                    <td>{{ $item->frequency ?? '—' }}</td>
                                    <td>{{ $item->duration ?? '—' }}</td>
                                    <td>{{ $item->instructions ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @else
            <div class="empty-state">{{ __('pdf.no_prescriptions_recorded') }}</div>
        @endif
    </div>

    <!-- Spectacle Prescriptions -->
    <div class="section">
        <h2 class="section-title">{{ __('workspace.spectacles') }}</h2>
        @if($visit->spectaclePrescriptions->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>{{ __('spectacles.eye') }}</th>
                        <th>{{ __('spectacles.sphere') }}</th>
                        <th>{{ __('spectacles.cylinder') }}</th>
                        <th>{{ __('spectacles.axis') }}</th>
                        <th>{{ __('spectacles.add') }}</th>
                        <th>{{ __('spectacles.prism') }}</th>
                        <th>{{ __('spectacles.pd') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visit->spectaclePrescriptions as $spectacle)
                        <tr>
                            <td>{{ __("examination.{$spectacle->eye}") }}</td>
                            <td>{{ $spectacle->sphere ?? '—' }}</td>
                            <td>{{ $spectacle->cylinder ?? '—' }}</td>
                            <td>{{ $spectacle->axis ?? '—' }}</td>
                            <td>{{ $spectacle->add_power ?? '—' }}</td>
                            <td>{{ $spectacle->prism ?? '—' }}</td>
                            <td>{{ $spectacle->pupillary_distance ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">{{ __('pdf.no_spectacles_recorded') }}</div>
        @endif
    </div>

    <!-- Footer with Signature -->
    <div class="footer">
        <div class="signature-area">
            <div class="signature-box">
                <div class="signature-line">
                    {{ __('pdf.doctor_signature') }}<br>
                    {{ $visit->doctor->name }}
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    {{ __('pdf.date') }}<br>
                    {{ now()->format('d/m/Y') }}
                </div>
            </div>
        </div>
        {{-- <div style="text-align: center; margin-top: 20px;">
            {{ __('pdf.report_validation') }}
        </div> --}}
    </div>
</body>
</html>

