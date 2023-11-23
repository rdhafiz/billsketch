<template>

    <div class="row">

        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center" style="height: 200px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Total Invoices</strong></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center" style="height: 200px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Total Pending Invoices</strong></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center" style="height: 200px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Total Paid Invoices</strong></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center" style="height: 200px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Total Recurring Invoices</strong></h3>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center" style="height: 450px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Chart - Invoices by month (Current Year)</strong></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center" style="height: 400px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Chart - Invoices by status</strong></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center" style="height: 400px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Chart - Invoices by category</strong></h3>
                </div>
            </div>
        </div>


    </div>

</template>
<script>
export default {
    components: {},
    created() {
        window.scroll(0, 0);
    }
}
</script>
