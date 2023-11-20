<template>

    <div class="w-100 py-5" :class="bg">
        <div class="w-100 d-inline-block py-5">
            <div class="w-100 d-inline-block py-5">
                <div class="w-100 d-inline-block py-5">
                    <h1 class="m-0 p-0 text-center"><strong>{{ title }}</strong></h1>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
export default {
    props: ['bg', 'title'],
    methods: {}
}
</script>
