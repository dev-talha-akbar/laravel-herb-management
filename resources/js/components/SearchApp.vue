<template>
  <div class="search-app">
    <search-bar
      :type="type"
      :selectedSigns="selectedSigns"
      :options="signs"
      @typeUpdated="setType($event)"
      @signsUpdated="setSelectedSigns($event)"
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
      selectedSigns: []
    };
  },
  mounted() {
    axios
      .get("/signs")
      .then(response => response.data)
      .then(signs => (this.signs = signs));
  },
  methods: {
    search(signs, type) {
      if (signs.length > 0) {
        this.loading = true;

        axios
          .post("/search", {
            signs: signs.map(sign => sign.id),
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
      this.search(this.selectedSigns, this.type);
    },
    setType(type) {
      this.type = type;
      this.search(this.selectedSigns, this.type);
    }
  }
};
</script>
