<script lang="ts" setup>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '../Layout.vue';

interface User {
    first_name: string;
    last_name: string;
    email?: string;
    slug: string;
}

interface Tag {
    id: number;
    name: string;
}

interface Post {
    id: number;
    title: string;
    slug: string;
    body: string;
    published_at: string;
    meta_title?: string;
    meta_description?: string;
    tags?: Tag[];
}

const props = defineProps<{
    user: User;
    post: Post;
}>();

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <Layout>
        <Head
            :title="
                props.post.meta_title ||
                `${props.post.title} - ${props.user.first_name} ${props.user.last_name}`
            "
        >
            <meta
                v-if="props.post.meta_description"
                :content="props.post.meta_description"
                name="description"
            />
        </Head>

        <div class="header">
            <h1>{{ props.user.first_name }} {{ props.user.last_name }}</h1>
            <p>Blog</p>
        </div>

        <article class="section post-content">
            <h2 class="post-title">{{ props.post.title }}</h2>
            <div class="meta">
                Published on {{ formatDate(props.post.published_at) }}
            </div>

            <div v-if="props.post.tags && props.post.tags.length" class="tags">
                <span v-for="tag in props.post.tags" :key="tag.id">
                    {{ tag.name }}
                </span>
            </div>

            <div class="post-body" v-html="props.post.body"></div>
        </article>

        <div class="footer">
            <Link :href="`/${props.user.slug}/blog`" class="back-link">
                ← Back to Blog
            </Link>
            <span class="separator">|</span>
            <Link :href="`/${props.user.slug}`" class="back-link">
                Portfolio
            </Link>
        </div>
    </Layout>
</template>

<style scoped>
.post-content {
    margin-bottom: 40px;
}

.post-title {
    font-size: 28px;
    margin-bottom: 12px;
    line-height: 1.3;
}

.meta {
    margin-bottom: 20px;
}

.tags {
    margin-bottom: 20px;
}

.post-body {
    font-size: 16px;
    line-height: 1.8;
    margin-top: 30px;
}

.post-body :deep(h1),
.post-body :deep(h2),
.post-body :deep(h3),
.post-body :deep(h4),
.post-body :deep(h5),
.post-body :deep(h6) {
    margin-top: 24px;
    margin-bottom: 12px;
    font-weight: bold;
}

.post-body :deep(h1) {
    font-size: 24px;
}

.post-body :deep(h2) {
    font-size: 22px;
}

.post-body :deep(h3) {
    font-size: 20px;
}

.post-body :deep(p) {
    margin-bottom: 16px;
}

.post-body :deep(ul),
.post-body :deep(ol) {
    margin-bottom: 16px;
    margin-left: 24px;
}

.post-body :deep(li) {
    margin-bottom: 8px;
}

.post-body :deep(code) {
    background-color: #f4f4f4;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
    font-size: 14px;
}

.post-body :deep(pre) {
    background-color: #f4f4f4;
    padding: 16px;
    border-radius: 4px;
    overflow-x: auto;
    margin-bottom: 16px;
}

.post-body :deep(pre code) {
    background-color: transparent;
    padding: 0;
}

.post-body :deep(blockquote) {
    border-left: 4px solid #ddd;
    padding-left: 16px;
    margin: 16px 0;
    color: #666;
    font-style: italic;
}

.post-body :deep(img) {
    max-width: 100%;
    height: auto;
    margin: 20px 0;
}

.footer {
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid #ddd;
    text-align: center;
}

.back-link {
    color: #666;
    font-size: 14px;
}

.separator {
    margin: 0 10px;
    color: #ddd;
}
</style>
