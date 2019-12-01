<template>
  <div class="searchbar">
    <div class="d-flex">
      <v-select
        placeholder="Enter signs and symptoms to search for herbs"
        multiple
        :options="signs"
        :value="selectedSigns"
        @input="$emit('signsUpdated', $event)"
        class="searchinput"
        :getOptionLabel="getOptionLabel"
        :getOptionKey="getOptionKey"
      ></v-select>
      <v-select
        placeholder="Results type"
        :value="type"
        :options="['Herb Formula', 'Herb']"
        @input="$emit('typeUpdated', $event)"
        class="typeinput"
        :clearable="false"
      ></v-select>
    </div>
    <a
      href="javascript:void(0)"
      @click="$emit('advancedSearchToggled')"
      class="advanced-search"
      v-if="type === 'Herb'"
    >{{ !advancedSearch ? 'More search options' : 'Clear additional search' }}</a>
    <div class="d-flex" v-if="advancedSearch">
      <v-select
        placeholder="Hormones"
        multiple
        :options="hormones"
        :value="selectedHormones"
        @input="$emit('hormonesUpdated', $event)"
        class="searchinput"
        :getOptionLabel="getOptionLabel"
        :getOptionKey="getOptionKey"
      ></v-select>
      <v-select
        placeholder="Chemical Composition"
        multiple
        :options="chemicalComposition"
        :value="selectedChemicalComposition"
        @input="$emit('chemicalCompositionUpdated', $event)"
        class="searchinput"
        :getOptionLabel="getOptionLabel"
        :getOptionKey="getOptionKey"
      ></v-select>
      <v-select
        placeholder="Pharmacology"
        multiple
        :options="pharmacology"
        :value="selectedPharmacology"
        @input="$emit('pharmacologyUpdate', $event)"
        class="searchinput"
        :getOptionLabel="getOptionLabel"
        :getOptionKey="getOptionKey"
      ></v-select>
      <v-select
        placeholder="Antibiotic Strains"
        multiple
        :options="antibioticStrains"
        :value="selectedAntibioticStrains"
        @input="$emit('antibioticStrainsUpdated', $event)"
        class="searchinput"
        :getOptionLabel="getOptionLabel"
        :getOptionKey="getOptionKey"
      ></v-select>
    </div>
  </div>
</template>
<style lang="scss" scoped>
.advanced-search {
  padding: 5px;
  display: inline-block;
}
.searchbar {
  padding: 5px 0;
}
.searchinput {
  flex: 1;
  margin-right: 10px;
}
.searchinput:first-child {
  margin-right: 10px !important;
}
.searchinput:last-child {
  margin-right: 0;
}
.typeinput {
  width: 200px;
}
</style>
<script>
export default {
  props: [
    "type",
    "signs",
    "selectedSigns",
    "advancedSearch",
    "hormones",
    "chemicalComposition",
    "pharmacology",
    "antibioticStrains",
    "selectedHormones",
    "selectedChemicalComposition",
    "selectedPharmacology",
    "selectedAntibioticStrains"
  ],
  mounted() {
    console.log("Component mounted.");
  },
  methods: {
    getOptionLabel(option) {
      return option.value;
    },
    getOptionKey(option) {
      return option.id;
    }
  }
};
</script>
