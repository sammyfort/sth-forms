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
export interface Category extends ModelI{
    name: string;
    price: number
    slug: string
    description: string
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
    referral_code: string
    referral_link: string
    points: number
    is_referrer_points_settled: boolean
    services: ServiceI
    jobs: JobI
    businesses: BusinessI
    signboards: SignboardI
    products: ProductI
    points_in_cedis: number
    country: CountryI
}

export interface CountryI extends ModelI{
    name: string
    iso2: string
    iso3: string
    phonecode: string
    currency: string
    currency_symbol: string
    regions: RegionI[]
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




export interface RegionI extends ModelI {
    name: string;
    slug: string;
    country: CountryI,
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
    value: number|string
}


export type PaymentStatusI = null | 'success' | 'failed' | 'cancelled' | 'pending';
