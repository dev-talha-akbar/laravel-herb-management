<template>
    <div class="herb">
        <h5 class="card-title">{{ english_name }}</h5>
        <div class="row">
            <div class="col-7">
                <table class="herbdetails table table-borderless table-sm">
                    <tbody>
                        <tr>
                            <th>English Name</th>
                            <td>{{ english_name }}</td>
                        </tr>
                        <tr>
                            <th>Chinese Name</th>
                            <td>{{ chinese_name }}</td>
                        </tr>
                        <tr>
                            <th>Pharmaceutical Name</th>
                            <td>{{ pharmaceutical_name }}</td>
                        </tr>
                        <tr>
                            <th>Literal Name</th>
                            <td>{{ literal_name }}</td>
                        </tr>
                        <tr>
                            <th>Signs / Symptoms</th>
                            <td>{{ signs }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ categories }}</td>
                        </tr>
                        <tr>
                            <th>Dosage</th>
                            <td>{{ dosage }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-5">
                <div class="herbconstituents">
                    <b>Chemical Constituents of this herb</b>
                    <div class="flex herbconstituentimages">
                        <div
                            class="constituent"
                            v-for="constituent in constituent_images"
                            :key="constituent"
                        >
                            <img :src="`/storage/${constituent}`" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="#" class="card-link">View Details</a>
    </div>
</template>
<script>
export default {
    props: ["data"],
    data() {
        return {
            ...this.data
        };
    },
    mounted() {
        console.log("Component mounted.");
    },
    computed: {
        categories() {
            return this.items
                .filter(item => item.type === "categories")
                .map(item => item.value)
                .join(", ");
        },
        signs() {
            return this.items
                .filter(item => item.type === "signs_symptoms")
                .map(item => item.value)
                .join(", ");
        }
    },
    methods: {}
};
</script>

<style lang="scss" scoped>
.herb {
    margin-bottom: 10px;
    .herbdetails {
        margin-left: -5px;
    }
    .formulatag {
        font-weight: normal;
        position: relative;
        top: -1px;
    }
    .herbconstituents {
        margin-top: 5px;
        .herbconstituentimages {
            margin: 0 -4px;
            display: flex;
        }
    }
    .constituent {
        img {
            width: 28px;
            height: 28px;
        }

        margin: 4px;
    }
}
</style>
