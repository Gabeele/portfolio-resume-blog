<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $resume->user->first_name }} {{ $resume->user->last_name }} - Resume</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.5;
            color: #000;
            padding: 0.75in;
            max-width: 8.5in;
            margin: 0 auto;
            background: #fff;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #000;
        }

        .header h1 {
            font-size: 24pt;
            font-weight: bold;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .contact {
            font-size: 11pt;
            color: #333;
        }

        .contact-line {
            display: inline;
            margin: 0 8px;
        }

        .contact-line:first-child {
            margin-left: 0;
        }

        .contact-separator {
            margin: 0 4px;
        }

        /* Sections */
        .section {
            margin-bottom: 18px;
        }

        .section-title {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
            padding-bottom: 3px;
            border-bottom: 1.5px solid #000;
            letter-spacing: 1px;
        }

        /* Summary */
        .summary p {
            font-size: 11pt;
            text-align: justify;
            margin-bottom: 8px;
        }

        /* Experience Items */
        .experience-item {
            margin-bottom: 14px;
        }

        .experience-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 2px;
        }

        .experience-title {
            font-size: 12pt;
            font-weight: bold;
        }

        .experience-dates {
            font-size: 10pt;
            font-style: italic;
            color: #333;
        }

        .experience-company {
            font-size: 11pt;
            font-style: italic;
            margin-bottom: 4px;
        }

        .experience-location {
            font-size: 10pt;
            color: #555;
            margin-bottom: 4px;
        }

        .experience-description {
            font-size: 11pt;
            text-align: justify;
        }

        /* Education Items */
        .education-item {
            margin-bottom: 12px;
        }

        .education-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 2px;
        }

        .education-degree {
            font-size: 12pt;
            font-weight: bold;
        }

        .education-dates {
            font-size: 10pt;
            font-style: italic;
            color: #333;
        }

        .education-school {
            font-size: 11pt;
            font-style: italic;
            margin-bottom: 4px;
        }

        .education-description {
            font-size: 11pt;
            text-align: justify;
        }

        /* Skills */
        .skills-list {
            font-size: 11pt;
            line-height: 1.6;
        }

        .skill-item {
            display: inline;
        }

        .skill-item:after {
            content: " • ";
            margin: 0 6px;
        }

        .skill-item:last-child:after {
            content: "";
        }

        /* Projects */
        .project-item {
            margin-bottom: 14px;
        }

        .project-title {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .project-role {
            font-size: 10pt;
            font-style: italic;
            color: #555;
            margin-bottom: 4px;
        }

        .project-description {
            font-size: 11pt;
            text-align: justify;
            margin-bottom: 4px;
        }

        .project-links {
            font-size: 10pt;
            color: #0066cc;
        }

        .project-links a {
            color: #0066cc;
            text-decoration: none;
        }

        /* Certifications */
        .certificates-list {
            list-style: none;
            padding: 0;
        }

        .certificates-list li {
            font-size: 11pt;
            margin-bottom: 6px;
            padding-left: 15px;
            position: relative;
        }

        .certificates-list li:before {
            content: "•";
            position: absolute;
            left: 0;
        }

        /* References */
        .references-list {
            list-style: none;
            padding: 0;
        }

        .references-list li {
            font-size: 11pt;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .reference-name {
            font-weight: bold;
        }

        .reference-title {
            font-style: italic;
        }

        /* Print optimization */
        @media print {
            body {
                padding: 0.5in;
            }
        }

        @page {
            size: letter;
            margin: 0;
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <h1>{{ $resume->user->first_name }} {{ $resume->user->last_name }}</h1>

    <div class="contact">
        @if($resume->user->email)
            <span class="contact-line">{{ $resume->user->email }}</span>
        @endif

        @if($resume->user->phone)
            @if($resume->user->email)<span class="contact-separator">|</span>@endif
            <span class="contact-line">{{ $resume->user->phone }}</span>
        @endif

        @php
            $addressParts = array_filter([
                $resume->user->city ?: null,
                $resume->user->region ?: null,
                $resume->user->country ?: null,
            ]);
        @endphp

        @if(!empty($addressParts))
            @if($resume->user->email || $resume->user->phone)<span class="contact-separator">|</span>@endif
            <span class="contact-line">{{ implode(', ', $addressParts) }}</span>
        @endif
    </div>
</div>

<!-- Summary -->
@if($resume->summaries->isNotEmpty())
    <div class="section summary">
        <h2 class="section-title">Professional Summary</h2>
        @foreach($resume->summaries as $summary)
            <p>{{ $summary->body }}</p>
        @endforeach
    </div>
@endif

<!-- Work Experience -->
@if($resume->workExperiences->isNotEmpty())
    <div class="section">
        <h2 class="section-title">Professional Experience</h2>
        @foreach($resume->workExperiences as $work)
            <div class="experience-item">
                <div class="experience-header">
                    <span class="experience-title">{{ $work->role }}</span>
                    <span class="experience-dates">
                        {{ $work->start->format('M Y') }} - {{ $work->end ? $work->end->format('M Y') : 'Present' }}
                    </span>
                </div>
                <div class="experience-company">{{ $work->business }}</div>
                @if($work->location)
                    <div class="experience-location">{{ $work->location }}</div>
                @endif
                @if($work->description)
                    <div class="experience-description">{{ $work->description }}</div>
                @endif
            </div>
        @endforeach
    </div>
@endif

<!-- Education -->
@if($resume->education->isNotEmpty())
    <div class="section">
        <h2 class="section-title">Education</h2>
        @foreach($resume->education as $edu)
            <div class="education-item">
                <div class="education-header">
                    <span class="education-degree">{{ $edu->certificate }}</span>
                    <span class="education-dates">
                        {{ $edu->start->format('Y') }} - {{ $edu->end ? $edu->end->format('Y') : 'Present' }}
                    </span>
                </div>
                <div class="education-school">{{ $edu->school }}</div>
                @if($edu->description)
                    <div class="education-description">{{ $edu->description }}</div>
                @endif
            </div>
        @endforeach
    </div>
@endif

<!-- Skills -->
@if($resume->skills->isNotEmpty())
    <div class="section">
        <h2 class="section-title">Skills</h2>
        <div class="skills-list">
            @foreach($resume->skills as $skill)
                <span class="skill-item">
                    {{ $skill->name }}@if($skill->proficiency) ({{ $skill->proficiency->value }})@endif
                </span>
            @endforeach
        </div>
    </div>
@endif

<!-- Projects -->
@if($resume->projects->isNotEmpty())
    <div class="section">
        <h2 class="section-title">Projects</h2>
        @foreach($resume->projects as $project)
            <div class="project-item">
                <div class="project-title">{{ $project->title }}</div>
                @if($project->role)
                    <div class="project-role">{{ $project->role }}</div>
                @endif
                @if($project->description)
                    <div class="project-description">{{ $project->description }}</div>
                @endif
                @if($project->url || $project->repo)
                    <div class="project-links">
                        @if($project->url)
                            <a href="{{ $project->url }}">{{ $project->url }}</a>
                        @endif
                        @if($project->url && $project->repo) | @endif
                        @if($project->repo)
                            <a href="{{ $project->repo }}">{{ $project->repo }}</a>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif

<!-- Certifications -->
@if($resume->certificates->isNotEmpty())
    <div class="section">
        <h2 class="section-title">Certifications</h2>
        <ul class="certificates-list">
            @foreach($resume->certificates as $cert)
                <li>
                    <strong>{{ $cert->name }}</strong>@if($cert->description) - {{ $cert->description }}@endif
                </li>
            @endforeach
        </ul>
    </div>
@endif

<!-- References -->
@if($resume->references->isNotEmpty())
    <div class="section">
        <h2 class="section-title">References</h2>
        <ul class="references-list">
            @foreach($resume->references as $reference)
                <li>
                    <div class="reference-name">{{ $reference->name }}</div>
                    @if($reference->title || $reference->company)
                        <div class="reference-title">
                            @if($reference->title){{ $reference->title }}@endif
                            @if($reference->title && $reference->company), @endif
                            @if($reference->company){{ $reference->company }}@endif
                        </div>
                    @endif
                    @if($reference->email)
                        <div>{{ $reference->email }}</div>
                    @endif
                    @if($reference->phone)
                        <div>{{ $reference->phone }}</div>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif

</body>
</html>
