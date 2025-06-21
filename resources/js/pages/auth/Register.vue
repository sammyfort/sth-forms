<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import Layout from '@/layouts/Layout.vue';
import InputText from '@/components/InputText.vue';
import { onMounted, ref } from 'vue';
import TypeIt from 'typeit';
import { getRandomAuthImage } from '@/lib/helpers';

const form = useForm({
    firstname: '',
    lastname: '',
    mobile: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const descriptionRef = ref<HTMLElement | null>(null)

onMounted(()=>{
    if (descriptionRef.value){
        new TypeIt(descriptionRef.value, {
            strings: [
                "Upload your signboard, reach more customers, or discover great businesses near you—it all starts with your account.",
                "Create your Signboard profile, showcase your location and reviews, and start attracting local customers who are ready to connect.",
                "Whether you're a customer looking for reliable businesses, or a business owner wanting more visibility—Signboard is your space."
            ],
            speed: 100,
            breakLines: false,
            loop: true
        }).go()
    }
})
</script>

<template>
    <Layout :center-y="true">
        <Head title="Register" />
        <div class="flex w-full max-w-[1400px] mx-auto">
            <div class="md:w-2/3 md:flex hidden">
                <div class="flex border w-full">
                    <img :src="getRandomAuthImage()" alt="Signup" class="w-[70%]">
                </div>
            </div>
            <form @submit.prevent="submit" class="flex flex-col gap-6 w-full justify-center md:w-1/3">
                <div>
                    <div class="text-3xl font-bold text-fade">Join Signboard</div>
                    <div class="text-fade" ref="descriptionRef"></div>
                </div>
                <div class="grid gap-6">
                    <InputText
                        :form="form"
                        model="firstname"
                        label="First Name"
                        autocomplete="first-name"
                        required
                        autofocus
                        placeholder="John"
                        :errors="form.errors"
                    />
                    <InputText
                        :form="form"
                        model="lastname"
                        label="Last Name"
                        autocomplete="last-name"
                        required
                        autofocus
                        placeholder="Doe"
                    />
                    <InputText
                        :form="form"
                        model="mobile"
                        label="Mobile Number"
                        autocomplete="mobile"
                        required
                        autofocus
                        placeholder="0542092800"
                    />
                    <InputText
                        :form="form"
                        model="email"
                        label="Email Address"
                        autocomplete="email"
                        required
                        autofocus
                        placeholder="email@mail.com"
                    />
                    <InputText
                        :form="form"
                        model="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Password"
                        label="Password"
                    />
                    <InputText
                        :form="form"
                        model="password_confirmation"
                        label="Confirm Password"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm password"
                        type="password"
                    />
                    <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Create Account
                    </Button>
                </div>
                <div class="text-center text-sm text-muted-foreground">
                    Already have an account?
                    <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="6">Log in</TextLink>
                </div>
            </form>
        </div>
    </Layout>
</template>
