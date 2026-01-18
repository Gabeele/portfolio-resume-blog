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
    tags?: Tag[];
}

interface PaginatedPosts {
    data: Post[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

const props = defineProps<{
    user: User;
    posts: PaginatedPosts;
}>();

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const stripHtml = (html: string) => {
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
};
</script>

<template>
    <Layout>
        <Head
            :title="`${props.user.first_name} ${props.user.last_name} - Blog`"
        />

        <div class="header">
            <h1>{{ props.user.first_name }} {{ props.user.last_name }}</h1>
            <p>Blog</p>
        </div>

        <div class="section">
            <div v-if="props.posts.data.length === 0" class="no-posts">
                <p>No blog posts published yet. Check back soon!</p>
            </div>

            <article
                v-for="post in props.posts.data"
                :key="post.id"
                class="post-item"
            >
                <h2 class="post-title">
                    <Link :href="`/${props.user.slug}/blog/${post.slug}`">
                        {{ post.title }}
                    </Link>
                </h2>
                <div class="meta">
                    {{ formatDate(post.published_at) }}
                </div>
                <div class="post-excerpt">
                    {{ stripHtml(post.body).substring(0, 200) }}...
                </div>
                <div v-if="post.tags && post.tags.length" class="tags">
                    <span v-for="tag in post.tags" :key="tag.id">
                        {{ tag.name }}
                    </span>
                </div>
                <Link
                    :href="`/${props.user.slug}/blog/${post.slug}`"
                    class="read-more"
                >
                    Read more →
                </Link>
            </article>
        </div>

        <!-- Pagination -->
        <div v-if="props.posts.last_page > 1" class="pagination">
            <Link
                v-if="props.posts.current_page > 1"
                :href="`/${props.user.slug}/blog?page=${props.posts.current_page - 1}`"
                class="pagination-link"
            >
                ← Previous
            </Link>
            <span class="pagination-info">
                Page {{ props.posts.current_page }} of
                {{ props.posts.last_page }}
            </span>
            <Link
                v-if="props.posts.current_page < props.posts.last_page"
                :href="`/${props.user.slug}/blog?page=${props.posts.current_page + 1}`"
                class="pagination-link"
            >
                Next →
            </Link>
        </div>

        <div class="footer">
            <Link :href="`/${props.user.slug}`" class="back-link">
                ← Back to Portfolio
            </Link>
        </div>
    </Layout>
</template>

<style scoped>
.post-item {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #eee;
}

.post-item:last-child {
    border-bottom: none;
}

.post-title {
    font-size: 24px;
    margin-bottom: 8px;
}

.post-title a {
    color: #333;
}

.post-title a:hover {
    color: #0066cc;
    text-decoration: none;
}

.post-excerpt {
    margin-top: 12px;
    margin-bottom: 12px;
    line-height: 1.6;
}

.read-more {
    display: inline-block;
    margin-top: 8px;
    color: #0066cc;
    font-weight: 500;
}

.no-posts {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 40px 0;
    padding: 20px 0;
    border-top: 1px solid #ddd;
}

.pagination-link {
    color: #0066cc;
    font-weight: 500;
}

.pagination-info {
    color: #666;
    font-size: 14px;
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
</style>
