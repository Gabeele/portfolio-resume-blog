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
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 40px;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Header layout: avatar on the left, contact on the right */
        .header {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 20px;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f0f0;
            font-size: 36px;
            color: #555;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .header-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .header-info h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .contact {
            font-size: 14px;
            color: #666;
        }

        .contact .line {
            margin-bottom: 4px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        .summary p {
            text-align: justify;
        }

        .work-experience-item,
        .education-item,
        .project-item {
            margin-bottom: 20px;
        }

        .work-experience-item h3,
        .education-item h3,
        .project-item h3 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .meta {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }

        .description {
            font-size: 14px;
            margin-top: 8px;
        }

        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .skill-item {
            background-color: #f4f4f4;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 14px;
        }

        .certificates-list, .references-list {
            list-style: none;
        }

        .tags {
            margin-top: 10px;
            font-size: 12px;
            color: #666;
        }

        @media (max-width: 600px) {
            .header {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .header-info {
                align-items: center;
            }
        }
    </style>
</head>
<body>
<div class="header">
    {{-- Avatar column --}}
    @if($resume->user->avatar_url)
        <div class="avatar">
            <img src="{{ $resume->user->avatar_url }}"
                 alt="{{ $resume->user->first_name }} {{ $resume->user->last_name }}'s avatar">
        </div>
    @endif

    {{-- Contact / name column --}}
    <div class="header-info">
        <h1>{{ $resume->user->first_name }} {{ $resume->user->last_name }}</h1>

        <div class="contact">
            @if($resume->user->email)
                <div class="line">Email: <a href="mailto:{{ $resume->user->email }}">{{ $resume->user->email }}</a>
                </div>
            @endif

            @if($resume->user->phone)
                <div class="line">Phone: {{ $resume->user->phone }}</div>
            @endif

            {{-- Full address line composed only when values exist --}}
            @php
                $addressParts = array_filter([
                    $resume->user->street ?: null,
                    $resume->user->city ?: null,
                    $resume->user->region ?: null,
                    $resume->user->mailing_code ?: null,
                    $resume->user->country ?: null,
                ]);
            @endphp

            @if(!empty($addressParts))
                <div class="line">Address: {{ implode(', ', $addressParts) }}</div>
            @endif

            {{-- Helpful small tag for country-specific notes, e.g. UK --}}
            @if(strtolower($resume->user->country ?? '') === 'uk' || strtolower($resume->user->country ?? '') === 'united kingdom' || strtolower($resume->user->country ?? '') === 'gb')
                <div class="line">Note: UK address shown — ensure mailing code (postcode) format is correct.</div>
            @endif
        </div>
    </div>
</div>

{{-- Summary --}}
@if($resume->summaries->isNotEmpty())
    <div class="section summary">
        <h2 class="section-title">Summary</h2>
        @foreach($resume->summaries as $summary)
            <p>{{ $summary->body }}</p>
        @endforeach
    </div>
@endif

{{-- Work --}}
@if($resume->workExperiences->isNotEmpty())
    <div class="section">
        <h2 class="section-title">Work Experience</h2>
        @foreach($resume->workExperiences as $work)
            <div class="work-experience-item">
                <h3>{{ $work->role }} - {{ $work->business }}</h3>
                <div class="meta">
                    @if($work->location)
                        {{ $work->location }} |
                    @endif
                    {{ $work->start->format('M Y') }} -
                    {{ $work->end ? $work->end->format('M Y') : 'Present' }}
                </div>
                @if($work->description)
                    <div class="description">{{ $work->description }}</div>
                @endif
            </div>
        @endforeach
    </div>
@endif

{{-- Education --}}
@if($resume->education->isNotEmpty())
    <div class="section">
        <h2 class="section-title">Education</h2>
        @foreach($resume->education as $edu)
            <div class="education-item">
                <h3>{{ $edu->certificate }} - {{ $edu->school }}</h3>
                <div class="meta">
                    {{ $edu->start->format('Y') }} - {{ $edu->end ? $edu->end->format('Y') : 'Present' }}
                </div>
                @if($edu->description)
                    <div class="description">{{ $edu->description }}</div>
                @endif
            </div>
        @endforeach
    </div>
@endif

{{-- Skills --}}
@if($resume->skills->isNotEmpty())
    <div class="section">
        <h2 class="section-title">Skills</h2>
        <div class="skills-list">
            @foreach($resume->skills as $skill)
                <div class="skill-item">
                    {{ $skill->name }}
                    @if($skill->proficiency)
                        ({{ $skill->proficiency->value }})
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Projects --}}
@if($resume->projects->isNotEmpty())
    <div class="section">
        <h2 class="section-title">Projects</h2>
        @foreach($resume->projects as $project)
            <div class="project-item">
                <h3>{{ $project->title }}</h3>
                @if($project->role)
                    <div class="meta">Role: {{ $project->role }}</div>
                @endif
                @if($project->description)
                    <div class="description">{{ $project->description }}</div>
                @endif
                @if($project->url || $project->repo)
                    <div class="meta" style="margin-top: 5px;">
                        @if($project->url)
                            <a href="{{ $project->url }}">{{ $project->url }}</a>
                        @endif
                        @if($project->repo)
                                @if($project->url)
                                    |
                                @endif
                            <a href="{{ $project->repo }}">Repository</a>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif

{{-- Certifications --}}
@if($resume->certificates->isNotEmpty())
    <div class="section">
        <h2 class="section-title">Certifications</h2>
        <ul class="certificates-list">
            @foreach($resume->certificates as $cert)
                <li>
                    <strong>{{ $cert->name }}</strong>
                    @if($cert->description)
                        - {{ $cert->description }}
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif

{{-- References --}}
@if($resume->references->isNotEmpty())
    <div class="section">
        <h2 class="section-title">References</h2>
        <ul class="references-list">
            @foreach($resume->references as $reference)
                <li>
                    <strong>{{ $reference->name }}</strong><br>
                    @if($reference->title)
                        {{ $reference->title }}
                    @endif
                    @if($reference->company)
                        @ {{ $reference->company }}
                    @endif
                    @if($reference->email)
                        <br>Email: {{ $reference->email }}
                    @endif
                    @if($reference->phone)
                        <br>Phone: {{ $reference->phone }}
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif
</body>
</html>
