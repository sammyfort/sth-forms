<script setup lang="ts">
import {
    Dialog, DialogClose, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import InputText from '@/components/InputText.vue';
import { RatingI, ReviewI, SignboardI } from '@/types';
import { AutoplayType } from 'embla-carousel-autoplay';
import { useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { rateSignboard } from '@/lib/api';
import { Errors, Page, PageProps } from '@inertiajs/core';
import { toastError, toastSuccess } from '@/lib/helpers';
import StarRating from 'vue-star-rating'

type Props = {
    signboard: SignboardI,
    carouselPlugin?: AutoplayType
}
const props = defineProps<Props>()

type Form = {
    overall: number,
    customer_service: number,
    quality: number,
    price: number,
    communication: number,
    speed: number,
    review: string|null
}

const form = useForm<Form>({
    overall: 0,
    customer_service: 0,
    quality: 0,
    price: 0,
    communication: 0,
    speed: 0,
    review: ""
})

const review = ref<RatingI|unknown>(null)
const closeBtn = ref(null)

const emit = defineEmits<{
    (e: 'popoverOpen', value: boolean): void,
    (e: 'rated', value: SignboardI): void,
}>()

const submit = async ()=> {
    await rateSignboard(form, props.signboard.id, (response: Page<PageProps>)=>{
        toastSuccess(response.props.message)
        closeBtn?.value?.$el?.click()
        emit('rated', response.props.data.signboard as SignboardI)
    }, null, (errors: Errors)=>{
        for (const error in errors){
            toastError(errors[error])
            break;
        }
    })
}

onMounted(()=>{
    review.value = props.signboard.reviews?.length ? props.signboard.reviews[0] as unknown as ReviewI : null
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
    <Dialog
        @update:open="handleCarousel"
    >
        <DialogTrigger>
            <slot />
        </DialogTrigger>
        <DialogContent>
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
