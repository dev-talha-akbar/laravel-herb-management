<template>
  <div class="form-app">
    <div class="result" v-if="subId">
      <h4>Recommended for you</h4>
      <div class="row">
        <div class="col-6">
          <h3>Herbs</h3>
          <SearchResults :results="result.herbs" type="Herb" :nameSearch="false" :loading="false" />
        </div>
        <div class="col-6">
          <h3>Herb Formulas</h3>
          <SearchResults
            :results="result.herb_formulas"
            type="Herb Formula"
            :nameSearch="false"
            :loading="false"
          />
        </div>
      </div>
    </div>
    <h3 style="margin-bottom: 30px;">Patient Form</h3>
    <form class="form form-horizontal" @submit.prevent="newSubmission()">
      <div class="form-group row">
        <label for="plname" class="col-md-2 col-form-label">Patient's Last Name</label>
        <div class="col-md-10">
          <input type="text" id="plname" v-model="plname" class="form-control" required />
        </div>
      </div>
      <div class="form-group row">
        <label for="pfname" class="col-md-2 col-form-label">Patient's First Name</label>
        <div class="col-md-10">
          <input type="text" id="pfname" v-model="pfname" class="form-control" required />
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group row">
            <label for="pmname" class="col-md-4 col-form-label">Middle Name</label>
            <div class="col-md-8">
              <input type="text" id="pmname" v-model="pmname" class="form-control" required />
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group row">
            <label for="pdob" class="col-md-4 col-form-label">Patient's Date of Birth</label>
            <div class="col-md-8">
              <input type="text" id="pdob" v-model="pdob" class="form-control" required />
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group row">
            <label for="pcell" class="col-md-4 col-form-label">Telephone (Cell)</label>
            <div class="col-md-8">
              <input type="text" id="pcell" v-model="pcell" class="form-control" required />
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group row">
            <label for="pother" class="col-md-4 col-form-label">Telephone (Other)</label>
            <div class="col-md-8">
              <input type="text" id="pother" v-model="pother" class="form-control" required />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="pemail" class="col-md-2 col-form-label">Email</label>
        <div class="col-md-10">
          <input type="text" id="pemail" v-model="pemail" class="form-control" required />
        </div>
      </div>
      <div class="form-group row">
        <label for="paddress" class="col-md-2 col-form-label">Address</label>
        <div class="col-md-10">
          <input type="text" id="paddress" v-model="paddress" class="form-control" required />
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group row">
            <label for="pcity" class="col-md-6 col-form-label">City</label>
            <div class="col-md-6">
              <input type="text" id="pcity" v-model="pcity" class="form-control" required />
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group row">
            <label for="pstate" class="col-md-6 col-form-label">State</label>
            <div class="col-md-6">
              <input type="text" id="pstate" v-model="pstate" class="form-control" required />
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group row">
            <label for="pzip" class="col-md-6 col-form-label">Zip</label>
            <div class="col-md-6">
              <input type="text" id="pzip" v-model="pzip" class="form-control" required />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="pid" class="col-md-2 col-form-label">
          SSN or ID
          <small>(For Veterans)</small>
        </label>
        <div class="col-md-10">
          <input type="text" id="pid" v-model="pid" class="form-control" />
        </div>
      </div>
      <div class="form-group">
        <label for="preminder">How would you like the appointment reminders be sent?</label>
        <div class="options">
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="reminder"
              id="remtext"
              value="text"
            />
            <label class="form-check-label" for="remtext">Text</label>
          </div>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="reminder"
              id="rememail"
              value="email"
            />
            <label class="form-check-label" for="rememail">Email</label>
          </div>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="reminder"
              id="remphone"
              value="phone"
            />
            <label class="form-check-label" for="remphone">Phone</label>
          </div>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="reminder"
              id="remnone"
              value="none"
            />
            <label class="form-check-label" for="remnone">No Reminder Needed</label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="pmaincomp">What is your main complaint?</label>
        <textarea class="form-control" id="pmaincomp" v-model="pmaincomp" rows="5" required></textarea>
      </div>
      <div class="two-col">
        <div class="form-group">
          <label>Has the patient had or have any of the following?</label>
          <div class="two-col">
            <div class="form-check">
              <input
                class="form-check-input"
                v-model="q1"
                type="checkbox"
                id="q1-shunt"
                value="shunt"
              />
              <label class="form-check-label" for="q1-shunt">A Shunt</label>
            </div>
            <div class="form-check">
              <input
                class="form-check-input"
                type="checkbox"
                id="q1-brainsurgery"
                value="brainsurgery"
                v-model="q1"
              />
              <label class="form-check-label" for="q1-brainsurgery">Brain Surgery</label>
            </div>
            <div class="form-check">
              <input
                class="form-check-input"
                v-model="q1"
                type="checkbox"
                id="q1-pacemaker"
                value="pacemaker"
              />
              <label class="form-check-label" for="q1-pacemaker">A Pacemaker</label>
            </div>
            <div class="form-check">
              <input
                class="form-check-input"
                type="checkbox"
                id="q1-metalplates"
                value="metalplates"
                v-model="q1"
              />
              <label class="form-check-label" for="q1-metalplates">Metal Plates</label>
            </div>
            <div class="form-check">
              <input
                class="form-check-input"
                type="checkbox"
                id="q1-breastbuttaug"
                value="breastbuttaug"
                v-model="q1"
              />
              <label class="form-check-label" for="q1-breastbuttaug">Breast or Butt Augmentation</label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label>What's the patient blood type?</label>
          <br />
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" v-model="q2" id="q2-a" value="a" />
            <label class="form-check-label" for="q2-a">A</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" v-model="q2" id="q2-b" value="b" />
            <label class="form-check-label" for="q2-b">B</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" v-model="q2" id="q2-ab" value="ab" />
            <label class="form-check-label" for="q2-ab">AB</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" v-model="q2" id="q2-o" value="o" />
            <label class="form-check-label" for="q2-o">O</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" v-model="q2" id="q2-none" value="none" />
            <label class="form-check-label" for="q2-none">Not Sure</label>
          </div>
        </div>
        <div class="form-group">
          <label>What's the patient body temperature?</label>
          <br />
          <div class="form-check">
            <input class="form-check-input" v-model="q3" type="checkbox" id="q3-hot" value="hot" />
            <label class="form-check-label" for="q3-hot">Feel Like You Run Hot</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" v-model="q3" type="checkbox" id="q3-cold" value="cold" />
            <label class="form-check-label" for="q3-cold">Feel Like You Run Cold</label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              v-model="q3"
              type="checkbox"
              id="q3-hot-cold"
              value="hot-cold"
            />
            <label class="form-check-label" for="q3-hot-cold">Feel Like You Run Hot &amp; Cold</label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              v-model="q3"
              type="checkbox"
              id="q3-normal"
              value="normal"
            />
            <label
              class="form-check-label"
              for="q3-normal"
            >Feel Like Your Body Temperature is Normal</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" v-model="q3" type="checkbox" id="q3-none" value="none" />
            <label class="form-check-label" for="q3-none">Not Sure</label>
          </div>
        </div>
        <div class="form-group">
          <label>Does the patient have blood sugar problems?</label>
          <div class="two-col">
            <div class="form-check">
              <input
                class="form-check-input"
                v-model="q4"
                type="checkbox"
                id="q4-t1-diab"
                value="t1-diab"
              />
              <label class="form-check-label" for="q4-t1-diab">Type 1 Diabetes</label>
            </div>
            <div class="form-check">
              <input
                class="form-check-input"
                v-model="q4"
                type="checkbox"
                id="q4-t2-diab"
                value="t2-diab"
              />
              <label class="form-check-label" for="q4-t2-diab">Type 2 Diabetes</label>
            </div>
            <div class="form-check">
              <input
                class="form-check-input"
                v-model="q4"
                type="checkbox"
                id="q4-hypo"
                value="hypogyclemia"
              />
              <label class="form-check-label" for="q4-hypo">Hypoglycemia</label>
            </div>
            <div class="form-check">
              <input
                class="form-check-input"
                v-model="q4"
                type="checkbox"
                id="q4-hyper"
                value="hyperglycemia"
              />
              <label class="form-check-label" for="q4-hyper">Hyperglycemia</label>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label>Does the patient have any of the following?</label>
          <div class="two-col">
            <div class="form-check">
              <input
                class="form-check-input"
                v-model="q5"
                type="checkbox"
                id="q5-hep-b"
                value="hep-b"
              />
              <label class="form-check-label" for="q5-hep-b">Hepatitis B</label>
            </div>
            <div class="form-check">
              <input
                class="form-check-input"
                v-model="q5"
                type="checkbox"
                id="q5-hep-c"
                value="hep-c"
              />
              <label class="form-check-label" for="q5-hep-c">Hepatitis C</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" v-model="q5" type="checkbox" id="q5-hiv" value="hiv" />
              <label class="form-check-label" for="q5-hiv">HIV</label>
            </div>
            <div class="form-check">
              <input
                class="form-check-input"
                v-model="q5"
                type="checkbox"
                id="q5-cirrhosis"
                value="cirrhosis"
              />
              <label class="form-check-label" for="q5-cirrhosis">Cirrhosis</label>
            </div>
            <div class="form-check">
              <input
                class="form-check-input"
                v-model="q5"
                type="checkbox"
                id="q5-herpes"
                value="herpes"
              />
              <label class="form-check-label" for="q5-herpes">Herpes</label>
            </div>
            <div class="form-check">
              <input
                class="form-check-input"
                v-model="q5"
                type="checkbox"
                id="q5-cancer"
                value="cancer"
              />
              <label class="form-check-label" for="q5-cancer">Cancer</label>
            </div>
          </div>
        </div>
        <div class="form-group" v-for="group in sign_groups" :key="group.key">
          <label>{{ group.label }} (check any patient experiences)</label>
          <div class="two-col">
            <div class="form-check" v-for="sign in sign_groups_values[group.key]" :key="sign.id">
              <input
                class="form-check-input"
                v-model="sign_groups_form[group.key]"
                type="checkbox"
                :id="`${group.key}-${sign.id}`"
                :value="sign.id"
              />
              <label class="form-check-label" :for="`${group.key}-${sign.id}`">{{ sign.name }}</label>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group btn-submit">
        <button :disabled="submitting" class="btn btn-primary btn-lg btn-block">Submit</button>
      </div>
    </form>
  </div>
</template>
<script>
import SearchResults from "./SearchResults";

export default {
  components: { SearchResults },
  props: ["signs", "groups"],
  data() {
    let overrides = {};
    if (typeof submissionForm !== "undefined") {
      overrides = {
        ...JSON.parse(submissionForm.form),
        result: submissionForm.result
          ? JSON.parse(submissionForm.result)
          : { herbs: [], herb_formulas: [] },
        editing: true,
        subId: submissionForm.id
      };
    }

    let sign_groups = this.groups.map(group => ({
      label: group,
      key: group.toLowerCase().replace(" ", "-")
    }));

    let all_signs = this.signs;

    let sign_groups_values = sign_groups.reduce(
      (sign_groups_values, sign_group) => {
        sign_groups_values[sign_group.key] = all_signs.filter(
          sign => sign.group === sign_group.label
        );
        return sign_groups_values;
      },
      {}
    );

    let sign_groups_form = sign_groups.reduce(
      (sign_groups_form, sign_group) => {
        sign_groups_form[sign_group.key] = [];
        return sign_groups_form;
      },
      {}
    );

    let data = {
      plname: "",
      pfname: "",
      pmname: "",
      pdob: "",
      pcell: "",
      pother: "",
      pemail: "",
      paddress: "",
      pcity: "",
      pstate: "",
      pzip: "",
      pid: "",
      reminder: [],
      pmaincomp: "",
      q1: [],
      q2: [],
      q3: [],
      q4: [],
      q5: [],
      submitting: false,
      editing: false,
      sign_groups,
      sign_groups_values,
      sign_groups_form,
      subId: false,
      ...overrides
    };

    return data;
  },
  methods: {
    newSubmission() {
      const { submitting, editing, subId, ...form } = this.$data;
      let $p;

      const signsSelected = this.sign_groups.reduce((signsSelected, group) => {
        return [...signsSelected, ...this.sign_groups_form[group.key]];
      }, []);

      this.submitting = true;

      if (editing) {
        $p = axios.put("/submission/" + subId, {
          form: JSON.stringify(form),
          signsSelected
        });
      } else {
        $p = axios
          .post("/submit", {
            form: JSON.stringify(form)
          })
          .then(response => {
            window.location.href = "/submission/" + response.data;
          });
      }

      $p.finally(() => {
        this.submitting = false;
      });
    }
  }
};
</script>
<style>
form {
  padding-bottom: 200px;
}
.form-group > label {
  font-size: 0.8rem;
  margin-top: 0.25rem;
  font-weight: bold;
}
.form-check-inline {
  margin-right: 5rem;
}
.two-col {
  -webkit-column-count: 2;
  -moz-column-count: 2;
  column-count: 2;
}
.form > .two-col .form-group {
  display: inline-block;
  width: 100%;
}
.btn-submit {
  margin-top: 80px;
}
.searchresults {
  padding-top: 20px !important;
}
.result {
  margin-bottom: 30px;
}
</style>