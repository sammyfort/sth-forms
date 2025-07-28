import { toastError } from '@/lib/helpers';
import { InertiaForm } from '@inertiajs/vue3';
import { Page, Errors } from '@inertiajs/core';

export const getPromotedSignboards = async ()=>{
    const data = {
        signboards: []
    }
    const request = await fetch(route('signboards.promoted'))
    const response = await request.json()
    if (response.success){
        data.signboards = response.data.signboards
    }
    return data
}

export const getPromotedServices = async ()=>{
    const data = {
        services: []
    }
    const request = await fetch(route('services.promoted'))
    const response = await request.json()
    if (response.success){
        data.services = response.data.services
    }
    return data
}

export const getPromotedProducts = async ()=>{
    const data = {
        products: []
    }
    const request = await fetch(route('products.promoted'))
    const response = await request.json()
    if (response.success){
        data.products = response.data.products
    }
    return data
}

export const getPromotedJobs = async ()=>{
    const data = {
        jobs: []
    }
    const request = await fetch(route('jobs.promoted'))
    const response = await request.json()
    if (response.success){
        data.jobs = response.data.jobs
    }
    return data
}

export const rateRatable = async (
    form: InertiaForm<any>,
    signboardId: number,
    successCb?: CallableFunction|null,
    errCb?: CallableFunction|null,
    onErr?: CallableFunction|null)=>
{
    form.post(route('ratings.rate', signboardId), {
        onSuccess: (response: Page) => {
            if (response.props.success){
                if (successCb){
                    successCb(response)
                }
            }
            else if (response.props.message.length){
                toastError(response.props.message)
                if (errCb){
                    errCb(response)
                }
            }
        },
        onError: (errors: Errors)=>{
            if (onErr){
                onErr(errors)
            }
        },
        preserveScroll: true,
    })
}
