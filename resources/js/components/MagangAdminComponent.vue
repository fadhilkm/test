<template>
    <v-app>
      <v-container>
        <v-layout>
          <v-flex xs12>
             

             <v-data-table
                 v-model="selected"
                :headers="headers"
                :items="items"
                item-key="id"
                class="elevation-1"
                select-all
                :expand="expand"

                >
                <template slot="items" slot-scope="props">
                  <tr>
                   <td>
                    <v-checkbox
                      v-model="props.selected"
                      primary
                      hide-details
                    ></v-checkbox>
                  </td>
                  <td @click="setSubItem(props.index);props.expanded = !props.expanded">{{ props.item.users.name }}</td>
                  <td @click="setSubItem(props.index);props.expanded = !props.expanded">{{ props.item.asal }}</td>
                  <td @click="setSubItem(props.index);props.expanded = !props.expanded" v-html="props.item.konstruktor ? props.item.konstruktor.user.name:'<span class=\'red--text\'><i>--belum ada</i></span>'"></td>
                  <td @click="setSubItem(props.index);props.expanded = !props.expanded">{{ props.item.from.toLocaleString() }}</td>
                  <td @click="setSubItem(props.index);props.expanded = !props.expanded">{{ props.item.until.toLocaleString() }}</td>
                  <td @click="setSubItem(props.index);props.expanded = !props.expanded" v-if="props.item.status.code==-1">
                   <span class="red--text">{{ props.item.status.description }}</span>
                 </td>
                  <td @click="setSubItem(props.index);props.expanded = !props.expanded" v-else-if="props.item.status.code==0">
                    <span class="blue--text"> {{ props.item.status.description }}</span>
                </td>
                <td @click="setSubItem(props.index);props.expanded = !props.expanded" v-else>
                    <span class="green--text"> {{ props.item.status.description }}</span>
                </td>
                <td>
                  <v-btn @click="editKonstruktor(props.item)">Edit Konstruktor</v-btn>
                </td>
              </tr>
                </template>
                
                <template slot="expand" slot-scope="props">
                <v-card flat>
                  <v-card-text>
                      <v-form target="_blank" v-for="surat in magang.surats" :key="surat.id" method="POST" action="/admin/viewpdf">
                        <input type="hidden" name="_token" :value="csrf">
                        <input type="hidden" name="filename" :value="surat.path_upload">
                        <v-btn type="submit" color="info">{{surat.jenis_surat.name}}</v-btn>
                      </v-form>  

                  </v-card-text>
                </v-card>
               </template>

                 <template slot="footer">
                  <td :colspan="headers.length+1">
                    <v-btn color="info"  @click="submitValidasi(true)" :disabled="selected.length> 0 ? false:true">Validasi</v-btn>
                      <v-btn color="error"  @click="submitValidasi(false)" :disabled="selected.length> 0 ? false:true">Belum Validasi</v-btn>
                    <v-btn color="success" @click="submitCompleted(true)" :disabled="selected.length > 0 ? false:true">Selesai</v-btn>
                    <v-btn color="error" @click="submitCompleted(false)" :disabled="selected.length > 0 ? false:true">Belum Selesai</v-btn>
                     <v-btn color="warning" @click="hapus" :disabled="selected.length > 0 ? false:true">Hapus</v-btn>


                  </td>
                </template>
          </v-data-table>  

          </v-flex>
        </v-layout>

      </v-container>
       
       <v-dialog
      v-model="dialog"
      width="499"
    >
     <v-card>
        <v-card-title
          class="headline grey lighten-2"
          primary-title
        >
          Edit Konstruktor
        </v-card-title>

        <v-card-text>
         <v-layout>
          <v-flex xs12>

            <v-card>
            <v-card-text primary-title>
               <span>Peserta magang: {{magang ? magang.users.name:''}}</span><br>
              <span>Tgl Mulai magang: {{magang ? magang.from.toLocaleString():''}} </span><br>
               <span>Tgl Selesai magang: {{magang ? magang.until.toLocaleString():''}} </span><br>
               <span>Asal: {{magang ? magang.asal:''}} </span>
            </v-card-text>
          </v-card>

          <v-form 
          ref="form"
          v-model="valid"
          lazy-validation
          >
           <v-select
           :rules="rules"
            v-model="konstruktor"
            :items="dataKonstruktor"
            item-value="id"
            item-text="name"
            label="Konstruktor"
            return-object
          ></v-select>
        </v-form>
          
          </v-flex>
         </v-layout>
          
           
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
           <v-btn
            color="primary"
            @click="submitKonstruktor"
          >
           Simpan
          </v-btn>
          <v-btn
            flat
            @click="dialog = false"
          >
           Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    </v-app>
</template>

<script>
    export default {
        props: ['dataMagang','dataKonstruktor'],
         data () {
          return {
            selected:[],
            expand:false,
            headers:[
              {
                text: 'Nama',
                align: 'left',
                sortable: false,
                value: 'name'
              },
              { text: 'Asal', value: 'asal' },
              { text: 'Konstruktor', value: 'konstruktor' },
              { text: 'Mulai Magang', value: 'from' },
              { text: 'Selesai Magang', value: 'until' },
              { text: 'Status Magang', value: 'status' },
              { text: 'Aksi', value: 'aksi' },
            ],
            rules: [
              v => !!v || 'Required'
            ],
            magang:null,
            items:[],
            csrf:document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            dialog:false,
            konstruktor:null,
            valid:true
          }
        },
        mounted() {

            console.log('Component mounted.');
            console.log(this.dataMagang);
            this.items = this.dataMagang;
            this.addItemsMagangStatus();
          
            //alert(this.items[0].status.code);

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
          hapus:function(){
             console.log(this.selected);
              //return;
              Swal.fire({
               title: 'Are you sure?',
               text: "You won't be able to revert this!",
               type: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yakin !',
               showLoaderOnConfirm: true,
               preConfirm: () => {
                  return axios.post("/admin/magang/delete", this.selected).then(res => {
                     if (res.data.error) {
                        throw new Error(res.data.error)
                     }

                  }).catch(error => {
                     Swal.showValidationMessage(
                        `Request failed: ${error}`
                     );
                  });
               },
               allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
               if (result.value) {
                  Swal.fire(
                     'Good job!',
                     'Berhasil edit data magang',
                     'success'
                  );
                  this.loadMagang();
               }
            });  
          },
          addItemsMagangStatus:function(){
              this.items.forEach((v,k)=>{
              v.status = {}
              v.status.code = null;
              v.status.description=null;
              if(v.is_validate){
                v.status.code = 0;
                v.status.description = "Sudah divalidasi";
              }
              else {
                v.status.code = -1;
                v.status.description = "Belum divalidasi";
              }
              if(v.is_completed){
                v.status.code = 1;
                v.status.description = "Magang Sudah Selesai";
              }
            });
          },
          submit:function(){
             if(this.$refs.form.validate()){
                this.$refs.form.$el.submit();
             }
          },
          loadMagang:function(){
            axios.get('/admin/magang/load').then((res)=>{
              this.items = res.data;
              this.addItemsMagangStatus();
            });

          },
          submitValidasi:function(validate){
              console.log(this.selected);
              //return;
              let data={'validate':validate,'data':this.selected}
              Swal.fire({
               title: 'Are you sure?',
               text: "You won't be able to revert this!",
               type: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yakin !',
               showLoaderOnConfirm: true,
               preConfirm: () => {
                  return axios.post("/admin/magang/validasi", data).then(res => {
                     if (res.data.error) {
                        throw new Error(res.data.error)
                     }

                  }).catch(error => {
                     Swal.showValidationMessage(
                        `Request failed: ${error}`
                     );
                  });
               },
               allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
               if (result.value) {
                  Swal.fire(
                     'Good job!',
                     'Berhasil edit data magang',
                     'success'
                  );
                  this.loadMagang();

               }
            });  
          },
          editKonstruktor:function(magang){
            this.magang=magang;
            this.dialog=true;
            this.konstruktor=magang.konstruktor ? magang.konstruktor.user:null;
            //console.log(magang.konstruktor.user);
          },
          submitKonstruktor:function(){
              console.log(this.konstruktor);
              if (!this.$refs.form.validate()) {
                 return;
              }
             let data={'magang':this.magang,'user':this.konstruktor};
              Swal.fire({
               title: 'Are you sure?',
               text: "You won't be able to revert this!",
               type: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yakin !',
               showLoaderOnConfirm: true,
               preConfirm: () => {
                  return axios.post("/admin/konstruktor/addtomagang", data).then(res => {
                     if (res.data.error) {
                        throw new Error(res.data.error)
                     }

                  }).catch(error => {
                     Swal.showValidationMessage(
                        `Request failed: ${error}`
                     );
                  });
               },
               allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
               if (result.value) {
                  Swal.fire(
                     'Good job!',
                     'Berhasil edit konstruktor',
                     'success'
                  );
                  this.loadMagang();this.dialog=false;
               }
            });  
          },
          submitCompleted:function(completed){
             console.log(this.selected);
              //return;
              let data={'completed':completed,'data':this.selected}
              Swal.fire({
               title: 'Are you sure?',
               text: "You won't be able to revert this!",
               type: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yakin !',
               showLoaderOnConfirm: true,
               preConfirm: () => {
                  return axios.post("/admin/magang/completed", data).then(res => {
                     if (res.data.error) {
                        throw new Error(res.data.error)
                     }

                  }).catch(error => {
                     Swal.showValidationMessage(
                        `Request failed: ${error}`
                     );
                  });
               },
               allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
               if (result.value) {
                  Swal.fire(
                     'Good job!',
                     'Berhasil edit data magang',
                     'success'
                  );
                  this.loadMagang();
               }
            });  
          },
          setSubItem:function(item_index){
            this.magang = this.dataMagang[item_index];
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
