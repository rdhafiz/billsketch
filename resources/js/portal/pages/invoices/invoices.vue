<template>

    <h1>Invoices</h1>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <div class="col-12 col-sm-6 col-lg-4 mb-3 mb-sm-0">
                    <input type="text" class="form-control" placeholder="Search">
                </div>
                <div class="col-12 col-sm-6 col-lg-8 text-end">
                    <router-link :to="{name: 'InvoiceCreate'}" class="btn btn-theme" style="width: 120px;">Create
                    </router-link>
                </div>
            </div>
            <div class="table-data table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>dd</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
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
