import { toastError } from '@/lib/helpers';
import { InertiaForm } from '@inertiajs/vue3';
import { Page, Errors } from '@inertiajs/core';

export const getPromotedSignboards = async ()=>{
    const request = await fetch(route('signboards.promoted'))
    const response = await request.json()
    return response.success ? response.data.signboards : {signboards: []}
}

export const getPromotedServices = async ()=>{
    const request = await fetch(route('services.promoted'))
    const response = await request.json()
    return response.success ? response.data.signboards : {signboards: []}
}

export const rateSignboard = async (
    form: InertiaForm<any>,
    signboardId: number,
    successCb?: CallableFunction|null,
    errCb?: CallableFunction|null,
    onErr?: CallableFunction|null)=>
{
    form.post(route('signboards.ratings', signboardId), {
        onSuccess: (response: Page) => {
            if (response.props.success){
                if (successCb){
                    successCb(response)
                }
            }
            else {
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
