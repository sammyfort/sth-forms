import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    success: boolean,
    message: string
};

export interface User {
    id: number;
    uuid: string;
    firstname: string;
    lastname: string;
    fullname: string;
    initials: string;
    mobile: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    password: string;
    is_active: boolean;
    created_by: string;
    deleted_by: string;
    facebook: string;
    x: string;
    instagram: string;
    linkedin: string;
    last_login: string;
    avatar?: MediaI,
}

export interface MediaI {
    "id": number,
    "model_type": string,
    "model_id": number,
    "uuid": string,
    "collection_name": string,
    "name": string,
    "file_name": string,
    "mime_type": string,
    "size": number,
    "generated_conversions": [],
    "order_column": 1,
    "created_at": string,
    "updated_at": string,
    "url": string,
    "original_url": string,
}

export type BreadcrumbItemType = BreadcrumbItem;
