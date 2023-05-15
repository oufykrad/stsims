<template>
    <Head title="Reimbursements" />
    <PageHeader :title="title" :items="items" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar">
            <div class="p-4 d-flex flex-column h-100">
                <div class="d-grid gap-2">
                    <b-button @click="create()" variant="primary" block>Create</b-button>
                </div>
            </div>
        </div>
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px)" ref="box">
            
        </div>
    </div>
    <Create :privileges="privileges" ref="create"/>
</template>
<script>
import Create from './Modals/Create.vue';
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    props: ['privileges'],
    components: { PageHeader, Create },
    data() {
        return {
            currentUrl: window.location.origin,
            title: "Reimbursements",
            items: [{text: "Reimbursements",href: "/",}, {text: "List",active: true, }, ],
        };
    },

    methods : {
        create(){
            this.$refs.create.set();
        }
    }
}
</script>
<style>
    .file-manager-sidebar {
        min-width: 450px;
        max-width: 450px;
        height: calc(100vh - 180px);
    }
    .dropdown-menu-lg {
        width: 89%;
    }
</style>

