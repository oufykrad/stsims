<template>

    <Head title="Financial Reports" />
    <PageHeader :title="title" :items="items" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar">
            <div class="p-4 d-flex flex-column h-100">
                 <ul class="list-group mb-4" style="cursor: pointer;">
                    <Link href="/accounting/allotments">
                        <li class="list-group-item"><i class="bx fs-18 bxs-award text-warning align-middle me-2"></i>Allotment</li>
                    </Link>
                     <Link href="/accounting/disbursements">
                        <li class="list-group-item"><i class="bx fs-18 bxs-graduation text-warning align-middle me-2"></i>Disbursement</li>
                    </Link>
                </ul>
            </div>
        </div>
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px)" ref="box">
            <slot />
        </div>
    </div>
</template>
<script>
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components: { PageHeader },
    data() {
        return {
            currentUrl: window.location.origin,
            title: "Financial Reports",
            items: [{
                text: "Lists",
                href: "/",
            }, {
                text: "Financial",
                active: true,
            }],
        };
    },
}
</script>
<style>
    .file-manager-sidebar {
        min-width: 450px;
        max-width: 450px;
        height: calc(100vh - 180px);
    }

</style>
