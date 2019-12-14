<template>
  <div class="herbformula">
    <h5 class="card-title">
      {{ english_name }}
      <span
        v-if="!nameSearch && signs_symptoms_count"
        class="badge badge-info"
      >{{ signs_symptoms_count }} Signs Matched</span>
    </h5>
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
            <tr v-if="view_more">
              <th>Formula Diagnosis</th>
              <td>{{ formula_diagnosis }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Tongue Diagnosis</th>
              <td>{{ tongue_diagnosis }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Pulse Diagnosis</th>
              <td>{{ pulse_diagnosis }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Formula Actions</th>
              <td>{{ formula_actions }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Herb-Drug Interaction</th>
              <td>{{ herb_drug_interaction }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Toxicity / Contraindications</th>
              <td>{{ toxicity_contraindications }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-5">
        <div class="herbsused">
          <b>Herbs Used in this Formula</b>
          <div class="flex herbsusedimages">
            <div class="herb" v-for="herb in herbs" :key="herb.id">
              <img :src="herb.dropbox_herb_image ? herb.dropbox_herb_image : '/img/no-preview.png'" />
              <div class="herbdetails">
                <b>{{ herb.english_name }}</b>
                <br />
                {{ herb.pivot.dosage_with_unit }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <a
      href="javascript:void(0)"
      class="card-link"
      @click="viewToggle()"
    >View {{ view_more ? 'Less' : 'More' }}</a>
  </div>
</template>
<script>
export default {
  props: ["data", "nameSearch"],
  data() {
    const getItemsOfType = type => {
      return this.data.items
        .filter(item => item.type === type)
        .map(item => item.value)
        .join(", ");
    };

    return {
      signs_symptoms_count,
      ...this.data,
      categories: getItemsOfType("categories"),
      signs: getItemsOfType("signs_symptoms"),
      formula_diagnosis: getItemsOfType("formula_diagnosis"),
      tongue_diagnosis: getItemsOfType("tongue_diagnosis"),
      pulse_diagnosis: getItemsOfType("pulse_diagnosis"),
      formula_actions: getItemsOfType("formula_actions"),
      herb_drug_interaction: getItemsOfType("herb_drug_interaction"),
      toxicity_contraindications: getItemsOfType("toxicity_contraindications"),
      view_more: false
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  methods: {
    viewToggle() {
      this.view_more = !this.view_more;
    }
  }
};
</script>

<style lang="scss" scoped>
.herbformula {
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
    img[src="/img/no-preview.png"] {
      border: 1px solid #ccc;
    }
    display: flex;
    font-size: 11px;
    margin: 4px;
  }
}
</style>
