<template>
  <div class="searchbar">
    <div class="d-flex">
      <v-select
        :placeholder="`Enter signs and symptoms to search for ${type.toLowerCase()}`"
        multiple
        :options="signs"
        :value="selectedSigns"
        @input="$emit('signsUpdated', $event)"
        class="searchinput"
        :getOptionLabel="getOptionLabel"
        :getOptionKey="getOptionKey"
        v-if="!nameSearch"
      ></v-select>
      <input
        v-if="nameSearch"
        class="form-control namesearchinput searchinput"
        @input="$emit('nameUpdated', $event.target.value)"
        :placeholder="`Enter a name to search for ${type.toLowerCase()}`"
        :value="nameToSearch"
      />
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
    <a
      href="javascript:void(0)"
      @click="$emit('nameSearchToggled')"
      class="name-search"
    >{{ !nameSearch ? `Search for a specific ${type.toLowerCase()}` : 'Search by signs' }}</a>
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
        @input="$emit('pharmacologyUpdated', $event)"
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
.advanced-search,
.name-search {
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
.namesearchinput {
  height: 32px;
}
.namesearchinput::-webkit-input-placeholder {
  color: #000;
}

.namesearchinput:-ms-input-placeholder {
  color: #000;
}

.namesearchinput::placeholder {
  color: #000;
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
    "nameSearch",
    "nameToSearch",
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
