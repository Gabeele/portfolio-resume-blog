<script lang="ts" setup>
import { Head, Link as InertiaLink } from '@inertiajs/vue3';
import Layout from '../Layout.vue';

interface User {
    first_name: string;
    last_name: string;
    email?: string;
    bio?: string;
    avatar_url?: string;
    slug: string;
}

interface Link {
    id: number;
    name: string;
    url: string;
    description?: string;
    order: number;
    is_active: boolean;
}

const props = defineProps<{
    user: User;
    links: Link[];
}>();
</script>

<template>
    <Layout>
        <Head
            :title="`${props.user.first_name} ${props.user.last_name} - Links`"
        />

        <div class="header">
            <div v-if="props.user.avatar_url" class="avatar">
                <img
                    :alt="props.user.first_name"
                    :src="props.user.avatar_url"
                />
            </div>
            <h1>{{ props.user.first_name }} {{ props.user.last_name }}</h1>
            <p v-if="props.user.bio" class="bio">{{ props.user.bio }}</p>
            <p v-else-if="props.user.email">{{ props.user.email }}</p>
        </div>

        <div class="section links-container">
            <div v-if="props.links.length === 0" class="no-links">
                <p>No links available at the moment.</p>
            </div>

            <a
                v-for="link in props.links"
                :key="link.id"
                :href="link.url"
                class="link-item"
                rel="noopener noreferrer"
                target="_blank"
            >
                <div class="link-content">
                    <div class="link-name">{{ link.name }}</div>
                    <div v-if="link.description" class="link-description">
                        {{ link.description }}
                    </div>
                </div>
                <div class="link-arrow">→</div>
            </a>
        </div>

        <div class="footer">
            <InertiaLink :href="`/${props.user.slug}`" class="back-link">
                ← Back to Portfolio
            </InertiaLink>
        </div>
    </Layout>
</template>

<style scoped>
.avatar {
    margin-bottom: 20px;
}

.avatar img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #333;
}

.bio {
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.links-container {
    max-width: 600px;
    margin: 0 auto;
}

.link-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    margin-bottom: 15px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    transition: all 0.2s ease;
}

.link-item:hover {
    background-color: #f0f0f0;
    border-color: #333;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.link-content {
    flex: 1;
}

.link-name {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 4px;
}

.link-description {
    font-size: 14px;
    color: #666;
}

.link-arrow {
    font-size: 24px;
    color: #666;
    margin-left: 15px;
}

.link-item:hover .link-arrow {
    color: #333;
    transform: translateX(5px);
    transition: transform 0.2s ease;
}

.no-links {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.footer {
    margin-top: 50px;
    padding-top: 20px;
    border-top: 1px solid #ddd;
    text-align: center;
}

.back-link {
    color: #666;
    font-size: 14px;
}
</style>
