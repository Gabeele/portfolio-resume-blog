<script lang="ts" setup>
import { Head } from '@inertiajs/vue3';
import Layout from '@/layouts/settings/Layout.vue';

interface Resume {
    user: {
        name: string
        email: string
    }
    summaries: { body: string }[]
    workExperiences: {
        role: string
        business: string
        location?: string
        start: string
        end?: string
        description?: string
    }[]
    education: {
        certificate: string
        school: string
        start: string
        end?: string
        description?: string
    }[]
    skills: {
        name: string
        proficiency?: { value: string }
    }[]
    projects: {
        title: string
        role?: string
        description?: string
        url?: string
        repo?: string
    }[]
    certificates: {
        name: string
        description?: string
    }[]
    references: {
        name: string
        title?: string
        company?: string
        email?: string
        phone?: string
    }[]
    tags?: string[]
}

const props = defineProps<{
    resume: Resume
}>()
</script>

<template>
    <Layout>
        <Head :title="`${props.resume.user.name} - Resume`" />

        <div class="header">
            <h1>{{ props.resume.user.name }}</h1>
            <p>{{ props.resume.user.email }}</p>
        </div>

        <!-- Summaries -->
        <div v-if="props.resume.summaries?.length" class="section summary">
            <h2 class="section-title">Summary</h2>
            <p v-for="(summary, index) in props.resume.summaries" :key="index">{{ summary.body }}</p>
        </div>

        <!-- Work Experience -->
        <div v-if="props.resume.workExperiences?.length" class="section">
            <h2 class="section-title">Work Experience</h2>
            <div v-for="(work, index) in props.resume.workExperiences" :key="index" class="work-experience-item">
                <h3>{{ work.role }} - {{ work.business }}</h3>
                <div class="meta">
                    <span v-if="work.location">{{ work.location }} | </span>
                    {{ new Date(work.start).toLocaleString('default', { month: 'short', year: 'numeric' }) }} -
                    {{ work.end ? new Date(work.end).toLocaleString('default', { month: 'short', year: 'numeric' }) : 'Present' }}
                </div>
                <div v-if="work.description" class="description">{{ work.description }}</div>
            </div>
        </div>

        <!-- Education -->
        <div v-if="props.resume.education?.length" class="section">
            <h2 class="section-title">Education</h2>
            <div v-for="(edu, index) in props.resume.education" :key="index" class="education-item">
                <h3>{{ edu.certificate }} - {{ edu.school }}</h3>
                <div class="meta">
                    {{ new Date(edu.start).getFullYear() }} - {{ edu.end ? new Date(edu.end).getFullYear() : 'Present' }}
                </div>
                <div v-if="edu.description" class="description">{{ edu.description }}</div>
            </div>
        </div>

        <!-- Skills -->
        <div v-if="props.resume.skills?.length" class="section">
            <h2 class="section-title">Skills</h2>
            <div class="skills-list">
                <div v-for="(skill, index) in props.resume.skills" :key="index" class="skill-item">
                    {{ skill.name }} <span v-if="skill.proficiency">({{ skill.proficiency.value }})</span>
                </div>
            </div>
        </div>

        <!-- Projects -->
        <div v-if="props.resume.projects?.length" class="section">
            <h2 class="section-title">Projects</h2>
            <div v-for="(project, index) in props.resume.projects" :key="index" class="project-item">
                <h3>{{ project.title }}</h3>
                <div v-if="project.role" class="meta">Role: {{ project.role }}</div>
                <div v-if="project.description" class="description">{{ project.description }}</div>
                <div v-if="project.url || project.repo" class="meta" style="margin-top:5px;">
                    <a v-if="project.url" :href="project.url">{{ project.url }}</a>
                    <span v-if="project.url && project.repo"> | </span>
                    <a v-if="project.repo" :href="project.repo">Repository</a>
                </div>
            </div>
        </div>

        <!-- Certificates -->
        <div v-if="props.resume.certificates?.length" class="section">
            <h2 class="section-title">Certifications</h2>
            <ul class="certificates-list">
                <li v-for="(cert, index) in props.resume.certificates" :key="index">
                    <strong>{{ cert.name }}</strong> <span v-if="cert.description">- {{ cert.description }}</span>
                </li>
            </ul>
        </div>

        <!-- References -->
        <div v-if="props.resume.references?.length" class="section">
            <h2 class="section-title">References</h2>
            <ul class="references-list">
                <li v-for="(ref, index) in props.resume.references" :key="index">
                    <strong>{{ ref.name }}</strong><br>
                    <span v-if="ref.title">{{ ref.title }}</span>
                    <span v-if="ref.company"> @ {{ ref.company }}</span>
                    <span v-if="ref.email"><br>Email: {{ ref.email }}</span>
                    <span v-if="ref.phone"><br>Phone: {{ ref.phone }}</span>
                </li>
            </ul>
        </div>

        <!-- Tags -->
        <div v-if="props.resume.tags?.length" class="tags">
            <span v-for="(tag, index) in props.resume.tags" :key="index">{{ tag }}</span>
        </div>
    </Layout>
</template>

<style scoped>
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
}</style>
