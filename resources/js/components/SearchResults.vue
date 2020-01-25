<template>
  <div class="searchresults">
    <div v-if="loading" class="d-flex justify-content-center align-items-center">
      <zoom-center-transition>
        <img src="/img/preloader.gif" />
      </zoom-center-transition>
    </div>
    <div v-if="!loading">
      <template v-if="results.length === 0">
        <zoom-center-transition>
          <div>
            <h2 class="noresults">Nothing to show just yet</h2>
            <img class="noresults-img" src="/img/no-results.svg" width="300" />
          </div>
        </zoom-center-transition>
      </template>
      <template v-if="results.length > 0">
        <h5>{{ results.length }} Total Results</h5>
        <fade-transition group>
          <div class="card" v-for="result in results" :key="result.id">
            <div class="card-body">
              <herb v-if="type === 'Herb'" :data="result" :nameSearch="nameSearch" />
              <herb-formula v-if="type === 'Herb Formula'" :data="result" :nameSearch="nameSearch" />
            </div>
          </div>
        </fade-transition>
      </template>
    </div>
  </div>
</template>
<style lang="scss" scoped>
.card {
  margin-bottom: 20px;
}
.searchresults {
  padding: 50px 0 0 0;
}
.noresults {
  color: #5acfb2;
  text-align: center;
}
.noresults + img {
  margin: auto;
  display: block;
}
</style>
<script>
import { ZoomCenterTransition, FadeTransition } from "vue2-transitions";

export default {
  props: ["results", "loading", "type", "nameSearch"],
  mounted() {
    console.log("Component mounted.");
  },
  components: { ZoomCenterTransition, FadeTransition }
};
</script>
