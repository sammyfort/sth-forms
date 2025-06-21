<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import Layout from '@/layouts/Layout.vue';
import TypeIt from 'typeit';
import { onMounted, ref } from 'vue';
import InputText from '@/components/InputText.vue';
import { getRandomAuthImage } from '@/lib/helpers';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
const descriptionRef = ref<HTMLElement|null>(null)

onMounted(()=>{
    if (descriptionRef.value){
        new TypeIt(descriptionRef.value, {
            strings: [
                "Signboard helps you reach local customers and lets users discover businesses near them—all from one platform.",
                "Upload your physical signboard, connect with nearby customers, and track your visibility with real-time views and reviews.",
                "Whether you're browsing or building, Signboard connects people with real-world businesses, powered by maps, reviews, and visibility."
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
        <Head title="Log in" />
        <div class="flex w-full max-w-[1400px] mx-auto">
            <div class="md:w-2/3 md:flex hidden">
                <div>
                    <img :src="getRandomAuthImage()" alt="Signup" class="w-[70%]">
                </div>
            </div>
            <form @submit.prevent="submit" class="flex flex-col gap-6 w-full md:w-1/3 justify-center">
                <div>
                    <div class="text-3xl font-bold text-fade">Login To Your Account</div>
                    <div class="text-fade" ref="descriptionRef"></div>
                </div>
                <div class="grid gap-6">
                    <InputText
                        :form="form"
                        model="email"
                        required
                        autofocus
                        autocomplete="email-mobile"
                        v-model="form.email"
                        placeholder="email@example.com or 0542092800"
                        label="Email Address / Mobile Number"
                    />
                    <div class="grid gap-2">
                        <div class="flex items-center justify-between">
                            <Label for="password">Password</Label>
                            <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm" :tabindex="5">
                                Forgot password?
                            </TextLink>
                        </div>
                        <Input
                            id="password"
                            type="password"
                            required
                            :tabindex="2"
                            autocomplete="current-password"
                            v-model="form.password"
                            placeholder="Password"
                        />
                        <InputError :message="form.errors.password" />
                    </div>
                    <div class="flex items-center justify-between">
                        <Label for="remember" class="flex items-center space-x-3">
                            <Checkbox id="remember" v-model="form.remember" :tabindex="3" />
                            <span>Remember me</span>
                        </Label>
                    </div>
                    <Button type="submit" class="mt-4 w-full" :tabindex="4" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Log in
                    </Button>
                </div>

                <div class="text-center text-sm text-muted-foreground">
                    Don't have an account?
                    <TextLink :href="route('register')" :tabindex="5">Sign up</TextLink>
                </div>
            </form>
        </div>
    </Layout>
</template>
