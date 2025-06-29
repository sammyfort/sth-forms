import type { Config } from 'ziggy-js';


export interface ModelI {
    id: number;
    uuid: string;
    createdBy?: User;
    created_by_id?: number;
    created_at: string;
    updated_at: string;
}

export interface Auth {
    user: User;
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

export interface User extends ModelI{
    firstname: string;
    lastname: string;
    fullname: string;
    initials: string;
    mobile: string;
    email: string;
    email_verified_at: string | null;
    password: string;
    is_active: boolean;
    facebook: string;
    x: string;
    instagram: string;
    linkedin: string;
    last_login: string;
    avatar?: MediaI,
}

export interface MediaI{
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

export interface BusinessI extends ModelI{
    slug: string;
    name: string;
    email: string;
    mobile: string;
    description: string;
    facebook?: string;
    x?: string;
    linkedin?: string;
    instagram?: string;
    verified?: boolean,
}

export interface SignboardCategoryI extends ModelI{
    slug: string
    name: string
    description: string
}

export interface RegionI extends ModelI{
    name: string
    slug: string
}

export interface SignboardI extends ModelI{
    business_id: number;
    business: BusinessI;
    categories: SignboardCategoryI[],
    region: RegionI;
    region_id: number;
    town: string;
    landmark: string;
    street: string;
    blk_number: string;
    gps: string;
}

export interface PaginationLinkI {
    active: boolean
    label: string
    url?: string|null
}

export interface PaginatedDataI<DT> {
    current_page: number,
    data?: DT[],
    first_page_url: string,
    from: number,
    last_page: number,
    last_page_url: string,
    links: PaginationLinkI[],
    next_page_url: string,
    path: string,
    per_page: number,
    prev_page_url: string,
    to: number,
    total: number,
}

