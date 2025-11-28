<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resume->name }} - Resume</title>
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

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 14px;
            color: #666;
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

        .summary {
            margin-bottom: 20px;
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

        .work-experience-item .meta,
        .education-item .meta {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }

        .work-experience-item .description,
        .education-item .description,
        .project-item .description {
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

        .certificates-list,
        .references-list {
            list-style: none;
        }

        .certificates-list li,
        .references-list li {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .references-list li {
            line-height: 1.8;
        }

        .tags {
            margin-top: 10px;
            font-size: 12px;
            color: #666;
        }

        .tags span {
            display: inline-block;
            margin-right: 8px;
            padding: 2px 8px;
            background-color: #e8e8e8;
            border-radius: 3px;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>{{ $resume->user->name }}</h1>
    <p>{{ $resume->user->email }}</p>
</div>

@if($resume->summaries->isNotEmpty())
    <div class="section summary">
        <h2 class="section-title">Summary</h2>
        @foreach($resume->summaries as $summary)
            <p>{{ $summary->body }}</p>
        @endforeach
    </div>
@endif

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

@if($resume->tags && count($resume->tags) > 0)
    <div class="tags">
        @foreach($resume->tags as $tag)
            <span>{{ $tag }}</span>
        @endforeach
    </div>
@endif
</body>
</html>
