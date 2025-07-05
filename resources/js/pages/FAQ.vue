<script setup lang="ts">
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion'
import Layout from '@/layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';

const intenseC = "font-bold text-secondary"
</script>

<template>
    <Head title="FAQ"/>
    <Layout>
        <div class="w-full">
            <div class="text-2xl font-bold text-center mb-5">Frequently Asked Questions</div>
            <div class="flex justify-center w-full">
                <div class="max-w-[500px] w-full">
                    <Accordion type="single" collapsible>
                        <AccordionItem value="item-1">
                            <AccordionTrigger class="text-lg font-semibold text-fade">
                                How do I register as a business owner on Signboard?
                            </AccordionTrigger>
                            <AccordionContent>
                                To register as a business owner, click the <span :class="intenseC">"Sign Up"</span> button on the homepage and select
                                the <span :class="intenseC">Business Owner</span> option. You can register using your <span :class="intenseC">email and password</span> or use your
                                <span :class="intenseC">Google account</span> (if enabled). Once registered, you’ll get access to a dashboard where
                                you can upload and manage your signboard listings.
                            </AccordionContent>
                        </AccordionItem>
                        <AccordionItem value="item-2">
                            <AccordionTrigger class="text-lg font-semibold text-fade">
                                Can users search for businesses by location on Signboard?
                            </AccordionTrigger>
                            <AccordionContent>
                                Yes! Users can search for businesses by  <span :class="intenseC">location, name, or category</span>. The app includes
                                an interactive <span :class="intenseC">Google Maps view</span> and filters to help users discover businesses in
                                specific areas or by their type of service.
                            </AccordionContent>
                        </AccordionItem>
                        <AccordionItem value="item-3">
                            <AccordionTrigger class="text-lg font-semibold text-fade">
                                How are reviews and ratings handled on Signboard?
                            </AccordionTrigger>
                            <AccordionContent>
                                Registered users can leave a <span :class="intenseC">star rating (1–5 stars)</span> and
                                write a <span :class="intenseC">text review</span> on any business profile.
                                Business profiles display the <span :class="intenseC">average rating</span> and the
                                total <span :class="intenseC">number of reviews</span>. Business owners receive a notification
                                when a new review is posted.
                            </AccordionContent>
                        </AccordionItem>
                        <AccordionItem value="item-4">
                            <AccordionTrigger class="text-lg font-semibold text-fade">
                                Can anyone upload a signboard on the platform?
                            </AccordionTrigger>
                            <AccordionContent>
                                Only <span :class="intenseC">registered business owners</span> can upload signboards.
                                To upload, you must create a business account and log in. Once logged in,
                                you can access the dashboard to upload your <span :class="intenseC">signboard image</span>,
                                add your <span :class="intenseC">business name,
                                category,</span> and <span :class="intenseC">location</span> with map coordinates.
                                Regular users can browse, search, and review businesses, but cannot create listings.
                            </AccordionContent>
                        </AccordionItem>
                        <AccordionItem value="item-5">
                            <AccordionTrigger class="text-lg font-semibold text-fade">
                                How are business profile views tracked on Signboard?
                            </AccordionTrigger>
                            <AccordionContent>
                                Each business profile tracks the number of <span :class="intenseC">views</span> it receives.
                                Owners can see the view count (e.g., 👁️ 150 Views) on their dashboard.
                                The system may also track <span :class="intenseC">unique views</span> to provide more
                                accurate insights into profile engagement.
                            </AccordionContent>
                        </AccordionItem>
                    </Accordion>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>

</style>
