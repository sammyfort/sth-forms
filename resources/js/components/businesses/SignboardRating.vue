<script setup lang="ts">
import StarRating from 'vue-star-rating'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import InputText from '@/components/InputText.vue';
import { Button } from '@/components/ui/button';
import { useForm } from '@inertiajs/vue3';
import { RatingI, ReviewI, SignboardI } from '@/types';
import { toastError, toastSuccess } from '@/lib/helpers';
import { onMounted, ref } from 'vue';
import { PopoverClose } from 'reka-ui';
import { AutoplayType } from 'embla-carousel-autoplay';


type Props = {
    signboard: SignboardI,
    carouselPlugin?: AutoplayType
}
const props = defineProps<Props>()

const form = useForm<{
    overall: number,
    customer_service: number,
    quality: number,
    price: number,
    communication: number,
    speed: number,
    review: string|null
}>({
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
    form.post(route('signboards.ratings', props.signboard.id), {
        onSuccess: (response) => {
            if (response.props.success){
                toastSuccess(response.props.message)
                closeBtn?.value?.$el?.click()
                emit('rated', response.props.data.signboard as SignboardI)
                form.reset()
            }
            else {
                toastError(response.props.message)
            }
        },
        onError: (errors)=>{
            for (const error in errors){
                toastError(errors[error])
                break;
            }
        },
        preserveScroll: true,
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
    <div>
        <Popover
            @update:open="handleCarousel"
        >
            <PopoverTrigger>
                <slot />
            </PopoverTrigger>
            <PopoverContent class="w-[20rem]">
                <form @submit.prevent="submit" class="grid grid-cols-1">
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
                    <div class="flex items-center border-b py-1.5 text-sm font-medium text-fade">
                        <InputText
                            textarea
                            container-class="w-full"
                            label="Review"
                            required
                            :form="form"
                            model="review"
                        />
                    </div>
                    <div class="pt-3 flex justify-between">
                        <PopoverClose as-child ref="closeBtn">
                            <Button type="button" size="sm" variant="secondary">Close</Button>
                        </PopoverClose>
                        <Button type="submit" size="sm" :processing="form.processing">Submit Review</Button>
                    </div>
                </form>
            </PopoverContent>
        </Popover>
    </div>
</template>

<style scoped>

</style>
