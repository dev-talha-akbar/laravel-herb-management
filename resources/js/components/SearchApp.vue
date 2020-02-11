<template>
  <div class="search-app">
    <search-bar
      :type="type"
      :nameSearch="nameSearch"
      :nameToSearch="nameToSearch"
      :options="allOptions"
      @typeUpdated="setType($event)"
      @optionsUpdated="setSelectedOptions($event)"
      @nameSearchToggled="toggleNameSearch()"
      @nameUpdated="setNameToSearch($event)"
    />
    <search-results
      :selectedSigns="selectedSigns"
      :nameSearch="nameSearch"
      :type="type"
      :loading="loading"
      :results="results"
    />
  </div>
</template>

<script>
function debounce(func, wait, immediate) {
  var timeout;
  return function() {
    var context = this,
      args = arguments;
    var later = function() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
}

export default {
  data() {
    return {
      loading: false,
      results: [],
      type: "Herb Formula",
      // signs: [],
      // hormones: [],
      // chemicalComposition: [],
      // antibioticStrains: [],
      // pharmacology: [],
      // advancedSearch: false,
      nameSearch: false,
      nameToSearch: "",
      // selectedSigns: [],
      // selectedHormones: [],
      // selectedChemicalComposition: [],
      // selectedPharmacology: [],
      // selectedAntibioticStrains: [],
      allOptions: [],
      selectedOptions: []
    };
  },
  computed: {
    selectedSigns() {
      return this.selectedOptions.filter(
        option => option.type === "signs_symptoms"
      );
    }
  },
  created() {
    this.searchByName = debounce(this.search, 1000);
  },
  mounted() {
    axios
      .get("/options")
      .then(response => response.data)
      .then(options => {
        // this.signs = options.signs_symptoms || [];
        // this.hormones = options.hormones || [];
        // this.chemicalComposition = options.chemical_composition || [];
        // this.pharmacology = options.pharmacology || [];
        // this.antibioticStrains = options.antibiotic_strains || [];
        const {
          signs_symptoms,
          hormones,
          chemical_composition,
          pharmacology,
          antibiotic_strains
        } = options;
        this.allOptions = [
          ...signs_symptoms,
          ...(hormones || []),
          ...(chemical_composition || []),
          ...(pharmacology || []),
          ...(antibiotic_strains || [])
        ];
      });

    window.document.body.classList.add("scroll");
  },
  methods: {
    // toggleAdvancedSearch() {
    //   if (this.advancedSearch) {
    //     this.selectedHormones = [];
    //     this.selectedChemicalComposition = [];
    //     this.selectedPharmacology = [];
    //     this.selectedAntibioticStrains = [];
    //     this.advancedSearch = false;
    //     this.search();
    //   } else {
    //     this.advancedSearch = true;
    //   }
    // },
    toggleNameSearch() {
      if (this.nameSearch) {
        this.nameToSearch = "";
        this.nameSearch = false;
        this.search();
      } else {
        this.nameSearch = true;
      }
    },
    search() {
      const {
        //selectedSigns,
        type,
        nameSearch,
        nameToSearch,
        selectedOptions
        // selectedHormones,
        // selectedChemicalComposition,
        // selectedPharmacology,
        // selectedAntibioticStrains,
        // advancedSearch
      } = this;

      // if (
      //   (!nameSearch && selectedSigns.length > 0) ||
      //   (nameSearch && nameToSearch.length > 0) ||
      //   selectedHormones.length > 0 ||
      //   selectedChemicalComposition.length > 0 ||
      //   selectedPharmacology.length > 0 ||
      //   selectedAntibioticStrains.length > 0
      // ) {
      if (
        (!nameSearch && selectedOptions.length > 0) ||
        (nameSearch && nameToSearch.length > 0)
      ) {
        this.loading = true;

        axios
          .post("/search", {
            // signs: selectedSigns.map(sign => sign.id),
            // hormones: selectedHormones.map(hormone => hormone.id),
            // chemicalComposition: selectedChemicalComposition.map(cc => cc.id),
            // pharmacology: selectedPharmacology.map(pharma => pharma.id),
            // antibioticStrains: selectedAntibioticStrains.map(as => as.id),
            // advancedSearch,
            options: selectedOptions.map(option => option.id),
            nameSearch,
            nameToSearch,
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
    setNameToSearch(name) {
      this.nameToSearch = name;
      this.searchByName();
    },
    // setSelectedSigns(signs) {
    //   this.selectedSigns = signs;
    //   this.search();
    // },
    setSelectedOptions(options) {
      this.selectedOptions = options;
      this.search();
    },
    setType(type) {
      this.type = type;

      if (type === "Herb Formula") {
        this.advancedSearch = false;
      }

      this.search();
    }
    // setSelectedHormones(hormones) {
    //   this.selectedHormones = hormones;
    //   this.search();
    // },
    // setSelectedChemicalComposition(chemicalComposition) {
    //   this.selectedChemicalComposition = chemicalComposition;
    //   this.search();
    // },
    // setSelectedPharmacology(pharmacology) {
    //   this.selectedPharmacology = pharmacology;
    //   this.search();
    // },
    // setSelectedAntibioticStrains(antibioticStrains) {
    //   this.selectedAntibioticStrains = antibioticStrains;
    //   this.search();
    // }
  }
};
</script>
