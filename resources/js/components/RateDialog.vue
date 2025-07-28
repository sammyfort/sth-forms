<script setup lang="ts">
import StarRating from 'vue-star-rating'
import InputText from '@/components/InputText.vue';
import { Button } from '@/components/ui/button';
import { useForm } from '@inertiajs/vue3';
import { ProductI, RatableItemsI, RatingI, ReviewI, SignboardI } from '@/types';
import { toastError, toastSuccess } from '@/lib/helpers';
import { onMounted, ref } from 'vue';
import { AutoplayType } from 'embla-carousel-autoplay';
import { rateRatable } from '@/lib/api';
import { Errors, Page } from '@inertiajs/core';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger
} from '@/components/ui/dialog';

type Props = {
    ratable: RatableItemsI
    ratable_type: "signboard" | "product"
    carouselPlugin?: AutoplayType
}

const props = defineProps<Props>()

type Form = {
    overall: number
    customer_service: number
    quality: number
    price: number
    communication: number
    speed: number
    review: string|null
    ratable_id: number
    ratable_type: Props['ratable_type']
}

const form = useForm<Form>({
    overall: 0,
    customer_service: 0,
    quality: 0,
    price: 0,
    communication: 0,
    speed: 0,
    review: "",
    ratable_id: props.ratable.id,
    ratable_type: props.ratable_type,
})
const review = ref<RatingI|unknown>(null)
const closeBtn = ref(null)

const emit = defineEmits<{
    (e: 'popoverOpen', value: boolean): void,
    (e: 'rated', value: number): void,
}>()

const submit = async ()=> {
    await rateRatable(form, props.ratable.id, (response: Page)=>{
        if (response.props.message){
            toastSuccess(response.props.message)
            emit('rated', response.props.data.ratable.total_average_rating )
        }
        closeBtn?.value?.$el?.click()
    }, null, (errors: Errors)=>{
        for (const error in errors){
            toastError(errors[error])
            break;
        }
    })
}

onMounted(()=>{
    review.value = props.ratable.reviews?.length ? props.ratable.reviews[0] as unknown as ReviewI : null
    if (review.value){
        const ratings = review.value.ratings
        form.review = review.value.review
        form.overall = ratings.find((rt: RatingI) => rt.key === 'overall')?.value ?? 0
        form.customer_service = ratings.find((rt: RatingI) => rt.key === 'customer_service')?.value ?? 0
        form.quality = ratings.find((rt: RatingI) => rt.key === 'quality')?.value ?? 0
        form.price = ratings.find((rt: RatingI) => rt.key === 'price')?.value ?? 0
        form.communication = ratings.find((rt: RatingI) => rt.key === 'communication')?.value ?? 0
        form.speed = ratings.find((rt: RatingI) => rt.key === 'speed')?.value ?? 0
    }

})

const handleCarousel = (isOpen: boolean) =>{
    emit('popoverOpen', isOpen)
}

</script>

<template>
    <Dialog @update:open="handleCarousel">
        <DialogTrigger>
            <slot />
        </DialogTrigger>
        <DialogContent>
            <DialogDescription />
            <DialogHeader>
                <DialogTitle>Write Review</DialogTitle>
            </DialogHeader>
            <div>
                <form @submit.prevent="submit" class="grid grid-cols-1" id="form">
                    <div class="flex flex-wrap items-center border-b py-1.5 text-sm font-medium text-fade">
                        <div class="w-1/2">Overall</div>
                        <div class="w-1/2 flex items-center">
                            <StarRating
                                v-model:rating="form.overall"
                                class="ms-auto"
                                :show-rating="false"
                                active-color="#009689"
                                :star-size="17"
                                :rounded-corners="true"
                                :animate="true"
                                :padding="3"
                            />
                        </div>
                    </div>
                    <div class="flex items-center border-b py-1.5 text-sm font-medium text-fade">
                        <div class="w-1/2">Customer Service</div>
                        <div class="w-1/2 flex items-center">
                            <StarRating
                                v-model:rating="form.customer_service"
                                class="ms-auto"
                                :show-rating="false"
                                active-color="#009689"
                                :star-size="17"
                                :rounded-corners="true"
                                :animate="true"
                                :padding="3"
                            />
                        </div>
                    </div>
                    <div class="flex items-center border-b py-1.5 text-sm font-medium text-fade">
                        <div class="w-1/2">Quality</div>
                        <div class="w-1/2 flex items-center">
                            <StarRating
                                v-model:rating="form.quality"
                                class="ms-auto"
                                :show-rating="false"
                                active-color="#009689"
                                :star-size="17"
                                :rounded-corners="true"
                                :animate="true"
                                :padding="3"
                            />
                        </div>
                    </div>
                    <div class="flex items-center border-b py-1.5 text-sm font-medium text-fade">
                        <div class="w-1/2">Price</div>
                        <div class="w-1/2 flex items-center">
                            <StarRating
                                v-model:rating="form.price"
                                class="ms-auto"
                                :show-rating="false"
                                active-color="#009689"
                                :star-size="17"
                                :rounded-corners="true"
                                :animate="true"
                                :padding="3"
                            />
                        </div>
                    </div>
                    <div class="flex items-center border-b py-1.5 text-sm font-medium text-fade">
                        <div class="w-1/2">Communication</div>
                        <div class="w-1/2 flex items-center">
                            <StarRating
                                v-model:rating="form.communication"
                                class="ms-auto"
                                :show-rating="false"
                                active-color="#009689"
                                :star-size="17"
                                :rounded-corners="true"
                                :animate="true"
                                :padding="3"
                            />
                        </div>
                    </div>
                    <div class="flex items-center border-b py-1.5 text-sm font-medium text-fade">
                        <div class="w-1/2">Speed</div>
                        <div class="w-1/2 flex items-center">
                            <StarRating
                                v-model:rating="form.speed"
                                class="ms-auto"
                                :show-rating="false"
                                active-color="#009689"
                                :star-size="17"
                                :rounded-corners="true"
                                :animate="true"
                                :padding="3"
                            />
                        </div>
                    </div>
                    <div class="flex items-center py-1.5 text-sm font-medium text-fade">
                        <InputText
                            textarea
                            container-class="w-full"
                            label="Review"
                            required
                            :form="form"
                            model="review"
                        />
                    </div>
                </form>
            </div>
            <DialogFooter class="flex">
                <div class="flex gap-3 justify-between w-full">
                    <DialogClose as-child ref="closeBtn">
                        <Button type="button" size="sm" variant="destructive">Close</Button>
                    </DialogClose>
                    <Button type="submit" form="form" size="sm" :processing="form.processing">Submit Review</Button>
                </div>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<style scoped>

</style>
