<template>
    <v-app>
      <v-container>

        <v-layout v-if="dataMagang">
           <v-flex xs12>
            <v-card v-if="dataMagang.is_completed" color="green darken-2" class="white--text">
              <v-card-title primary-title>
                <div>
                  <div class="headline">Magang Sudah Selesai</div>
                  <span>Mulai Magang: {{dataMagang.from}}</span><br>
                  <span>Selesai Magang: {{dataMagang.until}}</span><br>
                  <!--   <v-btn>Lihat Surat Permohonan</v-btn><br>
                     <v-btn >Lihat Surat Permohonan</v-btn> -->
                </div>
              </v-card-title>
              <v-card-actions>
              </v-card-actions>
            </v-card>
            <v-card v-else-if="dataMagang.is_validate" color="blue darken-2" class="white--text">
              <v-card-title primary-title>
                <div>
                  <div class="headline">Magang Sudah Divalidasi</div>
                  <span>Mulai Magang: {{dataMagang.from}}</span><br>
                  <span>Selesai Magang: {{dataMagang.until}}</span><br>
                  <!--   <v-btn>Lihat Surat Permohonan</v-btn><br>
                     <v-btn >Lihat Surat Permohonan</v-btn> -->
                </div>
              </v-card-title>
              <v-card-actions>
             
              </v-card-actions>
            </v-card>
             <v-card v-else color="blue darken-2" class="white--text">
              <v-card-title primary-title>
                <div>
                  <div class="headline">Menunggu Persetujuan Admin</div>
                  <span>Mulai Magang: {{dataMagang.from}}</span><br>
                  <span>Selesai Magang: {{dataMagang.until}}</span><br>
                  <!--   <v-btn>Lihat Surat Permohonan</v-btn><br>
                     <v-btn >Lihat Surat Permohonan</v-btn> -->
                </div>
              </v-card-title>
              <v-card-actions>
                   <v-btn >Edit</v-btn><v-btn flat>Hapus</v-btn>
              </v-card-actions>
            </v-card>
              
            </v-flex>
        </v-layout>
        <v-layout v-else>
          <v-flex xs6>
             <v-form
    ref="form"
    v-model="valid"
    lazy-validation 
    enctype="multipart/form-data" 
    method="POST"
    action="/magang"
  >

    <input type="hidden" name="from" :value="magang.from">
    <input type="hidden" name="until" :value="magang.until">
    <input type="hidden" name="_token" :value="csrf">

    <v-menu
          ref="menu1"
          v-model="menu1"
          :close-on-content-click="false"
          :nudge-right="40"
          lazy
          transition="scale-transition"
          offset-y
          full-width
          max-width="290px"
          min-width="290px"
        >
          <v-text-field
            slot="activator"
            :rules="[rules.required]"
            v-model="dateFormatted"
            label="Tgl Mulai Magang"
            hint="MM/DD/YYYY format"
            persistent-hint
            prepend-icon="event"
            @blur="magang.from = parseDate(dateFormatted)"
          ></v-text-field>
          <v-date-picker v-model="magang.from" no-title @input="menu1 = false"></v-date-picker>
        </v-menu>

         <v-menu
          ref="menu2"
          v-model="menu2"
          :close-on-content-click="false"
          :nudge-right="40"
          lazy
          transition="scale-transition"
          offset-y
          full-width
          max-width="290px"
          min-width="290px"
        >
          <v-text-field
            slot="activator"
            :rules="[rules.required]"
            v-model="dateFormatted2"
            label="Tgl Selesai Magang"
            hint="MM/DD/YYYY format"
            persistent-hint
            prepend-icon="event"
            @blur="magang.until = parseDate(dateFormatted2)"
          ></v-text-field>
          <v-date-picker v-model="magang.until" no-title @input="menu2 = false"></v-date-picker>
        </v-menu>

       <v-text-field
            label="Unggah Surat Permohonan"
            type="file"
            :rules="[rules.required]"
            name="surat_permohonan"
            prepend-icon="attach_file"
          ></v-text-field>

            <v-text-field
            label="Unggah Proposal"
            type="file"
             name="proposal"
            prepend-icon="attach_file"
          ></v-text-field>

             <v-btn
      :disabled="!valid"
      color="success"
      @click="submit"
    >
      Submit
    </v-btn>

    </v-form>  

          </v-flex>
        </v-layout>

      </v-container>
       

    </v-app>
</template>

<script>
    export default {
        props: ['dataMagang'],
         data () {
          return {
            valid: true,
            dateFormatted: this.formatDate(new Date().toISOString().substr(0, 10)),
            dateFormatted2: this.formatDate(new Date().toISOString().substr(0, 10)),
            magang:{
              from:new Date().toISOString().substr(0, 10),
              until:new Date().toISOString().substr(0, 10)
            },
            rules: {
              required: value => !!value || 'Required.'
            },
            menu1: false,
            menu2: false,
            items: [
              { title: 'Home', icon: 'dashboard' },
              { title: 'About', icon: 'question_answer' }
            ],
            right: null,
            csrf:document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        },
        mounted() {
            console.log('Component mounted.');
            console.log(this.dataMagang);
            //alert(this.dataMagang);

        }, 
        computed: {
          computedDateFormatted () {
            return this.formatDate(this.date)
          }
        },
         watch: {
          'magang.from':function(val, oldVal) {
            //alert(val);
            this.dateFormatted = this.formatDate(this.magang.from)
          },
          'magang.until':function(val, oldVal) {
            //alert(val);
            this.dateFormatted2 = this.formatDate(this.magang.until)
          },
        },
         methods: {
          submit:function(){
             if(this.$refs.form.validate()){
                   Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, submit it!'
                }).then((result) => {
                  if (result.value) {
                      this.$refs.form.$el.submit();
                  }
              });
             }
          },
          formatDate (date) {
            if (!date) return null

            const [year, month, day] = date.split('-')
            return `${month}/${day}/${year}`
          },
          parseDate (date) {
            if (!date) return null
            const [month, day, year] = date.split('/')
            return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
         }
        },
    }
</script>
