<script lang="ts" setup>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '../Layout.vue';
import { computed, ref, onMounted } from 'vue';

interface User {
    first_name: string;
    last_name: string;
    email: string;
    bio?: string;
    avatar_url?: string;
    slug: string;
    public_resume_id?: number;
}

interface Proficiency {
    value: string;
}

interface Skill {
    name: string;
    proficiency?: Proficiency;
}

interface WorkExperience {
    role: string;
    business: string;
    location?: string;
    start: string;
    end?: string;
    description?: string;
}

interface Education {
    certificate: string;
    school: string;
    start: string;
    end?: string;
    description?: string;
}

interface Project {
    title: string;
    role?: string;
    description?: string;
    url?: string;
    repo?: string;
    image?: string;
}

interface Certificate {
    name: string;
    description?: string;
}

interface Summary {
    body: string;
}

interface Resume {
    summaries?: Summary[];
    workExperiences?: WorkExperience[];
    education?: Education[];
    skills?: Skill[];
    projects?: Project[];
    certificates?: Certificate[];
}

interface Post {
    id: number;
    title: string;
    slug: string;
    published_at: string;
}

interface UserLink {
    id: number;
    name: string;
    url: string;
    description?: string;
}

const props = defineProps<{
    user: User;
    resume: Resume;
    recentPosts: Post[];
    links: UserLink[];
    hasBlog: boolean;
    hasLinks: boolean;
}>();

const fullName = computed(() => `${props.user.first_name} ${props.user.last_name}`);
const isScrolled = ref(false);

onMounted(() => {
    const handleScroll = () => {
        isScrolled.value = window.scrollY > 100;
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
});

const scrollToSection = (sectionId: string) => {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        year: 'numeric',
    });
};

const formatDateLong = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    });
};

const getResumeDownloadUrl = () => {
    if (props.user.public_resume_id) {
        return `/pdf/resume/${props.user.public_resume_id}`;
    }
    return null;
};
</script>

<template>
    <Layout>
        <Head :title="`${fullName} - Portfolio`" />

        <!-- Sticky Navigation -->
        <nav
            :class="[
                'fixed top-0 left-0 right-0 z-40 transition-all duration-300',
                isScrolled
                    ? 'bg-white/90 dark:bg-gray-900/90 backdrop-blur-md shadow-md'
                    : 'bg-transparent',
            ]"
        >
            <div class="max-w-4xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <button
                        @click="scrollToSection('top')"
                        class="font-bold text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                    >
                        {{ user.first_name }} {{ user.last_name }}
                    </button>
                    <div class="flex items-center gap-4">
                        <button
                            v-if="resume.projects && resume.projects.length > 0"
                            @click="scrollToSection('projects')"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                        >
                            Projects
                        </button>
                        <button
                            v-if="resume.workExperiences && resume.workExperiences.length > 0"
                            @click="scrollToSection('experience')"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                        >
                            Experience
                        </button>
                        <Link
                            v-if="hasBlog"
                            :href="`/${user.slug}/blog`"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                        >
                            Blog
                        </Link>
                        <Link
                            v-if="hasLinks"
                            :href="`/${user.slug}/links`"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                        >
                            Links
                        </Link>
                        <a
                            v-if="getResumeDownloadUrl()"
                            :href="getResumeDownloadUrl()!"
                            target="_blank"
                            class="px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors text-sm font-medium"
                        >
                            Download Resume
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section with Gradient -->
        <header id="top" class="mb-16 pt-24 relative">
            <!-- Gradient Background -->
            <div
                class="absolute inset-0 -z-10 opacity-20 dark:opacity-10"
                style="
                    background: linear-gradient(
                        135deg,
                        rgb(59, 130, 246) 0%,
                        rgb(147, 51, 234) 100%
                    );
                    mask-image: radial-gradient(
                        ellipse at top,
                        black 0%,
                        transparent 70%
                    );
                "
            ></div>

            <div class="flex flex-col md:flex-row items-start md:items-center gap-8 mb-8">
                <img
                    v-if="user.avatar_url"
                    :src="user.avatar_url"
                    :alt="fullName"
                    class="w-32 h-32 rounded-full object-cover border-4 border-blue-500 dark:border-blue-400 shadow-xl ring-4 ring-blue-500/20"
                />
                <div class="flex-1">
                    <h1
                        class="text-5xl md:text-6xl font-bold text-gray-900 dark:text-gray-100 mb-3 bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 bg-clip-text text-transparent"
                    >
                        {{ fullName }}
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-400 mb-4">
                        {{ user.email }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a
                            v-if="getResumeDownloadUrl()"
                            :href="getResumeDownloadUrl()!"
                            target="_blank"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 dark:bg-blue-500 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 transition-all shadow-lg hover:shadow-xl hover:scale-105 font-medium"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                            Download Resume
                        </a>
                        <Link
                            v-if="hasBlog"
                            :href="`/${user.slug}/blog`"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 transition-all hover:shadow-lg font-medium"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"
                                />
                            </svg>
                            Read My Blog
                        </Link>
                        <Link
                            v-if="hasLinks"
                            :href="`/${user.slug}/links`"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 transition-all hover:shadow-lg font-medium"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                />
                            </svg>
                            My Links
                        </Link>
                    </div>
                </div>
            </div>

            <p
                v-if="user.bio"
                class="text-xl text-gray-700 dark:text-gray-300 leading-relaxed max-w-3xl"
            >
                {{ user.bio }}
            </p>
        </header>

        <!-- About / Summary -->
        <section
            v-if="resume.summaries && resume.summaries.length > 0"
            class="mb-20"
        >
            <h2
                class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center gap-3"
            >
                <span
                    class="w-1 h-8 bg-gradient-to-b from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 rounded-full"
                ></span>
                About
            </h2>
            <div class="space-y-4">
                <p
                    v-for="(summary, index) in resume.summaries"
                    :key="index"
                    class="text-gray-700 dark:text-gray-300 leading-relaxed text-lg pl-4 border-l-2 border-gray-200 dark:border-gray-700"
                >
                    {{ summary.body }}
                </p>
            </div>
        </section>

        <!-- Featured Projects -->
        <section
            v-if="resume.projects && resume.projects.length > 0"
            id="projects"
            class="mb-20 scroll-mt-24"
        >
            <h2
                class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-8 flex items-center gap-3"
            >
                <span
                    class="w-1 h-8 bg-gradient-to-b from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 rounded-full"
                ></span>
                Featured Projects
            </h2>
            <div class="grid gap-6">
                <article
                    v-for="(project, index) in resume.projects"
                    :key="index"
                    class="group relative border-2 border-gray-200 dark:border-gray-700 rounded-xl p-6 hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 bg-white dark:bg-gray-800/50"
                >
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-blue-500/5 to-purple-500/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                    ></div>
                    <div class="relative">
                        <h3
                            class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-2"
                        >
                            {{ project.title }}
                        </h3>
                        <p
                            v-if="project.role"
                            class="text-sm text-blue-600 dark:text-blue-400 mb-3 font-medium"
                        >
                            {{ project.role }}
                        </p>
                        <p
                            v-if="project.description"
                            class="text-gray-700 dark:text-gray-300 mb-4 leading-relaxed"
                        >
                            {{ project.description }}
                        </p>
                        <div
                            v-if="project.url || project.repo"
                            class="flex flex-wrap gap-3"
                        >
                            <a
                                v-if="project.url"
                                :href="project.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors duration-200"
                            >
                                <span>View Project</span>
                                <svg
                                    class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-200"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                            </a>
                            <a
                                v-if="project.repo"
                                :href="project.repo"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-medium transition-colors duration-200"
                            >
                                <svg
                                    class="w-4 h-4"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"
                                    />
                                </svg>
                                <span>Source Code</span>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <!-- Work Experience -->
        <section
            v-if="resume.workExperiences && resume.workExperiences.length > 0"
            id="experience"
            class="mb-20 scroll-mt-24"
        >
            <h2
                class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-8 flex items-center gap-3"
            >
                <span
                    class="w-1 h-8 bg-gradient-to-b from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 rounded-full"
                ></span>
                Experience
            </h2>
            <div class="space-y-8 relative">
                <!-- Timeline line -->
                <div
                    class="absolute left-0 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 hidden md:block"
                ></div>

                <article
                    v-for="(work, index) in resume.workExperiences"
                    :key="index"
                    class="relative pl-0 md:pl-8"
                >
                    <!-- Timeline dot -->
                    <div
                        class="hidden md:block absolute left-0 top-2 w-3 h-3 bg-blue-600 dark:bg-blue-400 rounded-full -translate-x-[5px] ring-4 ring-white dark:ring-gray-900"
                    ></div>

                    <div
                        class="bg-white dark:bg-gray-800/50 rounded-lg p-6 border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400 transition-colors"
                    >
                        <h3
                            class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                        >
                            {{ work.role }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-2 font-medium">
                            {{ work.business }}
                            <span v-if="work.location"> • {{ work.location }}</span>
                        </p>
                        <p
                            class="text-sm text-gray-500 dark:text-gray-500 mb-3 flex items-center gap-2"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                            {{ formatDate(work.start) }} -
                            {{ work.end ? formatDate(work.end) : 'Present' }}
                        </p>
                        <p
                            v-if="work.description"
                            class="text-gray-700 dark:text-gray-300 leading-relaxed"
                        >
                            {{ work.description }}
                        </p>
                    </div>
                </article>
            </div>
        </section>

        <!-- Skills -->
        <section
            v-if="resume.skills && resume.skills.length > 0"
            class="mb-20"
        >
            <h2
                class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-8 flex items-center gap-3"
            >
                <span
                    class="w-1 h-8 bg-gradient-to-b from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 rounded-full"
                ></span>
                Skills
            </h2>
            <div class="flex flex-wrap gap-3">
                <span
                    v-for="(skill, index) in resume.skills"
                    :key="index"
                    class="px-5 py-2.5 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 text-gray-800 dark:text-gray-200 rounded-full text-sm font-medium border border-blue-200 dark:border-blue-700 hover:border-blue-500 dark:hover:border-blue-400 transition-all hover:shadow-md hover:scale-105"
                >
                    {{ skill.name }}
                    <span
                        v-if="skill.proficiency"
                        class="text-blue-600 dark:text-blue-400 ml-1"
                    >
                        • {{ skill.proficiency.value }}
                    </span>
                </span>
            </div>
        </section>

        <!-- Education -->
        <section
            v-if="resume.education && resume.education.length > 0"
            class="mb-20"
        >
            <h2
                class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-8 flex items-center gap-3"
            >
                <span
                    class="w-1 h-8 bg-gradient-to-b from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 rounded-full"
                ></span>
                Education
            </h2>
            <div class="space-y-6">
                <article
                    v-for="(edu, index) in resume.education"
                    :key="index"
                    class="bg-white dark:bg-gray-800/50 rounded-lg p-6 border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400 transition-colors"
                >
                    <h3
                        class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                    >
                        {{ edu.certificate }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-2 font-medium">
                        {{ edu.school }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mb-3">
                        {{ new Date(edu.start).getFullYear() }} -
                        {{
                            edu.end
                                ? new Date(edu.end).getFullYear()
                                : 'Present'
                        }}
                    </p>
                    <p
                        v-if="edu.description"
                        class="text-gray-700 dark:text-gray-300 leading-relaxed"
                    >
                        {{ edu.description }}
                    </p>
                </article>
            </div>
        </section>

        <!-- Certifications -->
        <section
            v-if="resume.certificates && resume.certificates.length > 0"
            class="mb-20"
        >
            <h2
                class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-8 flex items-center gap-3"
            >
                <span
                    class="w-1 h-8 bg-gradient-to-b from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 rounded-full"
                ></span>
                Certifications
            </h2>
            <ul class="space-y-3">
                <li
                    v-for="(cert, index) in resume.certificates"
                    :key="index"
                    class="flex items-start gap-3 text-gray-700 dark:text-gray-300"
                >
                    <svg
                        class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                        />
                    </svg>
                    <div>
                        <span class="font-semibold">{{ cert.name }}</span>
                        <span
                            v-if="cert.description"
                            class="text-gray-600 dark:text-gray-400"
                        >
                            - {{ cert.description }}
                        </span>
                    </div>
                </li>
            </ul>
        </section>

        <!-- Recent Blog Posts -->
        <section v-if="recentPosts.length > 0" class="mb-20">
            <div class="flex items-center justify-between mb-8">
                <h2
                    class="text-3xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-3"
                >
                    <span
                        class="w-1 h-8 bg-gradient-to-b from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 rounded-full"
                    ></span>
                    Recent Writing
                </h2>
                <Link
                    :href="`/${user.slug}/blog`"
                    class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium text-sm transition-colors duration-200 flex items-center gap-1"
                >
                    <span>View all</span>
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                </Link>
            </div>
            <div class="space-y-4">
                <Link
                    v-for="post in recentPosts"
                    :key="post.id"
                    :href="`/${user.slug}/blog/${post.slug}`"
                    class="block group"
                >
                    <article
                        class="border-l-4 border-gray-200 dark:border-gray-700 group-hover:border-blue-500 dark:group-hover:border-blue-400 pl-6 py-3 transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-800/50 rounded-r-lg"
                    >
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200"
                        >
                            {{ post.title }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">
                            {{ formatDateLong(post.published_at) }}
                        </p>
                    </article>
                </Link>
            </div>
        </section>

        <!-- Footer -->
        <footer
            class="pt-8 border-t border-gray-200 dark:border-gray-700 text-center text-gray-600 dark:text-gray-400"
        >
            <p>&copy; {{ new Date().getFullYear() }} {{ fullName }}</p>
        </footer>
    </Layout>
</template>

<style scoped>
/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}
</style>
