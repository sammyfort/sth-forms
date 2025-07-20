import type { Config } from 'ziggy-js';


export interface ModelI {
    id: number;
    uuid: string;
    createdBy?: User;
    created_by_id?: number;
    created_at: string;
    updated_at: string;
    created_at_str: string;
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
    message: string,
    data: {
        [key]: string
    }
};

export interface User extends ModelI {
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

export interface ServiceI extends ModelI{
    title: string;
    description: string;
    first_mobile: string;
    second_mobile: string;
    email: string;
    address: string;
    business_name?: string;
    region_id: number;
    region: RegionI;
    town: string;
    gps: string;
    user: User;
    featured: MediaI;
    gallery: MediaI[];
}

export interface MediaI {
    'id': number,
    'model_type': string,
    'model_id': number,
    'uuid': string,
    'collection_name': string,
    'name': string,
    'file_name': string,
    'mime_type': string,
    'size': number,
    'generated_conversions': [],
    'order_column': 1,
    'created_at': string,
    'updated_at': string,
    'url': string,
    'original_url': string,
}

export interface BusinessI extends ModelI {
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
    user: User,
    signboards: SignboardI
    user_id: number,
    initials: string,
    average_rating: number
}

export interface SignboardCategoryI extends ModelI {
    slug: string;
    name: string;
    description: string;
}

export interface RegionI extends ModelI {
    name: string;
    slug: string;
}

export interface SignboardSubscriptionPlanI extends ModelI {
    name: string;
    slug: string;
    description: string;
    price: number;
    number_of_days: number;
}

export interface SignboardSubscriptionI extends ModelI {
    id: number;
    signboard_id: number;
    plan_id: number;
    amount: number;
    signboard: SignboardI;
    plan: SignboardSubscriptionPlanI;
    payment_reference: string;
    payment_status: string;
    payment_channel: string;
    payment_platform: string;
    starts_at: string;
    ends_at: string;
    receipt_number: string;
    is_active: boolean;
    days_left: number;
    total_days: number;
}

export interface SignboardI extends ModelI {
    id: number;
    name: string;
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
    gps_lat: string,
    gps_lon: string,
    total_average_rating: number,
    reviews_count: number,
    slug: string,
    featured_url: string,
    views_count: number | null,
    active_subscription: SignboardSubscriptionI | null,
    gallery_urls: [],
    reviews: ReviewI[];
    subscriptions: SignboardSubscriptionI | []

}

export interface RatingI extends ModelI {
    key: 'overall' | 'customer_service' | 'quality' | 'price' | 'communication' | 'speed';
    review_id: number;
    value: number;
}

export interface ReviewI extends ModelI {
    approved: boolean
    department: stringn
    ratings: RatingI[]
    recommend: boolean
    review: string
    reviewable_id: string
    reviewable_type: string
    user_id: number,
    average_rating: number,
    user: User,
}

export interface PaginationLinkI {
    active: boolean;
    label: string;
    url?: string | null;
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

export interface AverageRatingsI {
    communication: number;
    customer_service: number;
    overall: number;
    price: number;
    quality: number;
    speed: number;
}

export interface RatingsDistributionI {
    1: number;
    2: number;
    3: number;
    4: number;
    5: number;
}

export type InputSelectOption = {
    label: string
    value: string
}

