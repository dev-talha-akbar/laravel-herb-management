<template>
  <div class="herbformula">
    <h5 class="card-title">{{ english_name }}</h5>
    <div class="row">
      <div class="col-7">
        <table class="formuladetails table table-borderless table-sm">
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
              <th>Signs / Symptoms</th>
              <td>{{ signs }}</td>
            </tr>
            <tr>
              <th>Category</th>
              <td>{{ categories }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-5">
        <div class="herbsused">
          <b>Herbs Used in this Formula</b>
          <div class="flex herbsusedimages">
            <div class="herb" v-for="herb in herbs" :key="herb.id">
              <img :src="`/storage/${herb.herb_image}`" />
              <div class="herbdetails">
                <b>{{ herb.english_name }}</b>
                <br />
                {{ herb.dosage_with_unit }}
              </div>
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
.herbformula {
  margin-bottom: 10px;
  .formuladetails {
    margin-left: -5px;
  }
  .formulatag {
    font-weight: normal;
    position: relative;
    top: -1px;
  }
  .herbsused {
    margin-top: 5px;
    .herbsusedimages {
      margin: 0 -4px;
      display: flex;
    }
  }
  .herb {
    img {
      width: 32px;
      height: 32px;
      margin-right: 4px;
    }
    display: flex;
    font-size: 11px;
    margin: 4px;
  }
}
</style>
