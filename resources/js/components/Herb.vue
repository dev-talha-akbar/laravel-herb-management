<template>
  <div class="herb">
    <h5 class="card-title">
      {{ english_name }}
      <span
        v-if="!nameSearch && signs_symptoms_count"
        class="badge badge-info"
      >{{ signs_symptoms_count }} Signs Matched</span>
    </h5>
    <div class="row">
      <div class="col-12">
        <table class="herbdetails table table-borderless table-sm">
          <tbody>
            <tr v-if="view_more">
              <th>English Name</th>
              <td>{{ english_name }}</td>
            </tr>
            <tr>
              <th>Chinese Name</th>
              <td>{{ chinese_name }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Pharmaceutical Name</th>
              <td>{{ pharmaceutical_name }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Literal Name</th>
              <td>{{ literal_name }}</td>
            </tr>
            <tr>
              <th>Signs / Symptoms</th>
              <td>
                <div style="position: relative; left: -5px">
                  <span
                    style="font-size: 0.9rem; margin-right: 5px;"
                    class="badge badge-info"
                    v-for="sign in matched_signs"
                    :key="sign"
                  >{{ sign }}</span>
                  <span
                    style="font-size: 0.9rem; margin-right: 5px;"
                    class="badge badge-default"
                    v-for="sign in not_matched_signs"
                    :key="sign"
                  >{{ sign }}</span>
                </div>
              </td>
            </tr>
            <tr v-if="view_more">
              <th>Category</th>
              <td>{{ categories }}</td>
            </tr>
            <tr>
              <th>Dosage</th>
              <td>{{ dosage_with_unit }} (Maximum: {{ max_dosage }}g)</td>
            </tr>
            <tr>
              <th>Administered</th>
              <td>{{ usage_label }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Formulas Found In</th>
              <td>{{ formulas.map(formula => formula.english_name).join(', ') }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Properties</th>
              <td>{{ properties }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Systems Affected</th>
              <td>{{ systems_affected }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Actions</th>
              <td>{{ actions }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Chemicial Composition</th>
              <td>{{ chemical_composition }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Pharmacology</th>
              <td>{{ pharmacology }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Antibiotic Strains</th>
              <td>{{ antibiotic_strains }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Hormones</th>
              <td>{{ hormones }}</td>
            </tr>
            <tr v-if="view_more">
              <th>Herb-Herb Interaction</th>
              <td>{{ herb_herb_interaction }}</td>
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
      <div class="col-12">
        <div class="herbconstituents">
          <div class="flex justify-content-end">
            <img width="96" :src="dropbox_herb_image" />
          </div>
          <div class="flex herbconstituentimages">
            <div
              class="constituent"
              v-for="constituent in dropbox_constituent_images"
              :key="constituent"
            >
              <img :src="constituent" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <a
      href="javascript:void(0)"
      class="card-link"
      @click="viewToggle()"
    >View {{ view_more ? "Less" : "More" }}</a>
  </div>
</template>
<script>
export default {
  props: ["data", "nameSearch", "selectedSigns"],
  data() {
    const getItemsOfType = type => {
      return this.data.items
        .filter(item => item.type === type)
        .map(item => item.value)
        .join(", ");
    };

    const getMatchedSigns = () => {
      return this.data.items
        .filter(
          item =>
            item.type === "signs_symptoms" &&
            this.selectedSigns.map(sign => sign.id).indexOf(item.id) > -1
        )
        .map(item => item.value);
    };

    const getNotMatchedSigns = () => {
      return this.data.items
        .filter(
          item =>
            item.type === "signs_symptoms" &&
            this.selectedSigns.map(sign => sign.id).indexOf(item.id) === -1
        )
        .map(item => item.value);
    };

    return {
      signs_symptoms_count: getMatchedSigns().length,
      ...this.data,
      categories: getItemsOfType("categories"),
      matched_signs: getMatchedSigns(),
      not_matched_signs: getNotMatchedSigns(),
      properties: getItemsOfType("properties"),
      systems_affected: getItemsOfType("systems_affected"),
      actions: getItemsOfType("actions"),
      chemical_composition: getItemsOfType("chemical_composition"),
      pharmacology: getItemsOfType("pharmacology"),
      antibiotic_strains: getItemsOfType("antibiotic_strains"),
      hormones: getItemsOfType("hormones"),
      herb_herb_interaction: getItemsOfType("herb_herb_interaction"),
      herb_drug_interaction: getItemsOfType("herb_drug_interaction"),
      toxicity_contraindications: getItemsOfType("toxicity_contraindications"),
      usage_label:
        this.data.usage !== null
          ? this.data.usage == "0"
            ? "Both Orally and Topically"
            : this.data.usage == "1"
            ? "Orally"
            : this.data.usage == "2"
            ? "Topically"
            : "Not Known"
          : "Not Known",
      view_more: false
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  computed: {},
  methods: {
    viewToggle() {
      this.view_more = !this.view_more;
    }
  }
};
</script>

<style lang="scss" scoped>
.herb {
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
      width: 64px;
      height: 64px;
    }

    margin: 4px;
  }
}
</style>
