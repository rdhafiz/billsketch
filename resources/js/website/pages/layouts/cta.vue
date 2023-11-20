<template>

    <div class="cta">
        <div class="img-bg">
            <img class="w-100" :src="'/assets/images/banner/home-banner.jpg'" alt="" loading="lazy">
        </div>
        <div class="cta-overlay">
            <div class="container-lg">
                <div class="cta-content">
                    <div class="w-100 text-center">
                        <h1 class="cta-title">{{ title }}</h1>
                        <h5 class="cta-sub-title">{{ sub_title }}</h5>
                        <router-link :to="{name:'Login'}" class="cta-action">{{ action_title }}</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
export default {
    props: ['title','sub_title','description','action_title'],
    methods: {}
}
</script>
