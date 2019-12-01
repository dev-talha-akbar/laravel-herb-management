<template>
  <div class="search-app">
    <search-bar
      :type="type"
      :selectedSigns="selectedSigns"
      :signs="signs"
      :advancedSearch="advancedSearch"
      :hormones="hormones"
      :chemicalComposition="chemicalComposition"
      :antibioticStrains="antibioticStrains"
      :pharmacology="pharmacology"
      @typeUpdated="setType($event)"
      @signsUpdated="setSelectedSigns($event)"
      @hormonesUpdated="setSelectedHormones($event)"
      @chemicalCompositionUpdated="setSelectedChemicalComposition($event)"
      @pharmacologyUpdated="setSelectedPharmacology($event)"
      @antibioticStrainsUpdated="setSelectedAntibioticStrains($event)"
      @advancedSearchToggled="toggleAdvancedSearch()"
    />
    <search-results :type="type" :loading="loading" :results="results" />
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      results: [],
      type: "Herb Formula",
      signs: [],
      hormones: [],
      chemicalComposition: [],
      antibioticStrains: [],
      pharmacology: [],
      advancedSearch: false,
      selectedSigns: [],
      selectedHormones: [],
      selectedChemicalComposition: [],
      selectedPharmacology: [],
      selectedAntibioticStrains: []
    };
  },
  mounted() {
    axios
      .get("/options")
      .then(response => response.data)
      .then(options => {
        this.signs = options.signs_symptoms || [];
        this.hormones = options.hormones || [];
        this.chemicalComposition = options.chemical_composition || [];
        this.pharmacology = options.pharmacology || [];
        this.antibioticStrains = options.antibiotic_strains || [];
      });

    window.document.body.classList.add("scroll");
  },
  methods: {
    toggleAdvancedSearch() {
      if (this.advancedSearch) {
        this.advancedSearch = false;
      } else {
        this.advancedSearch = true;
      }
    },
    search() {
      const {
        selectedSigns,
        type,
        selectedHormones,
        selectedChemicalComposition,
        selectedPharmacology,
        selectedAntibioticStrains,
        advancedSearch
      } = this;
      if (selectedSigns.length > 0) {
        this.loading = true;

        axios
          .post("/search", {
            signs: selectedSigns.map(sign => sign.id),
            hormones: selectedHormones.map(hormone => hormone.id),
            chemicalComposition: selectedChemicalComposition.map(cc => cc.id),
            pharmacology: selectedPharmacology.map(pharma => pharma.id),
            antibioticStrains: selectedAntibioticStrains.map(as => as.id),
            advancedSearch,
            type
          })
          .then(response => response.data)
          .then(results => {
            this.loading = false;
            this.results = results;
          });
      } else {
        this.loading = false;
        this.results = [];
      }
    },
    setSelectedSigns(signs) {
      this.selectedSigns = signs;
      this.search();
    },
    setType(type) {
      this.type = type;

      if (type === "Herb Formula") {
        this.advancedSearch = false;
      }

      this.search();
    },
    setSelectedHormones(hormones) {
      this.selectedHormones = hormones;
      this.search();
    },
    setSelectedChemicalComposition(chemicalComposition) {
      this.selectedChemicalComposition = chemicalComposition;
      this.search();
    },
    setSelectedPharmacology(pharmacology) {
      this.selectedPharmacology = pharmacology;
      this.search();
    },
    setSelectedAntibioticStrains(antibioticStrains) {
      this.selectedAntibioticStrains = antibioticStrains;
      this.search();
    }
  }
};
</script>
