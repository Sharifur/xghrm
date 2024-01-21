<template>
    <div class="media-upload-btn-wrapper">
        <div class="img-wrap" v-show="getOldImage?.image_id">
            <div class="rmv-span" @click="removeImage"><i class="fas fa-trash"></i></div>
            <div class="attachment-preview" >
                <div class="thumbnail">
                    <div class="centered">
                        <div class="icon-wrap" v-if="['pdf','doc','docx','txt','zip','csv','xlsx','xlsm','xlsb','xltx','pptx','pptm','ppt'].includes(getOldImage.type)">
                            <i class="fas fa-file file-icon"></i>
                            <span class="file-name">{{getOldImage.type}}</span>
                        </div>
                        <img v-else :src="getOldImage.img_url" :alt="getOldImage.alt">
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden"v-bind:modelValue="modelValue" @input="$emit('update:modelValue',$event.target.value)">
       <div class="btn-wrapper">
           <button type="button" class="media-upload-button" @click="openMediaModal">
               {{ buttonTextData }}
           </button>
       </div>
    </div>
    <Transition name="fade">
        <div class="media-modal-overlay" @click="modalClose" v-show="modalStatus"></div>
    </Transition>
    <Transition name="fade">
        <div class="media-modal-wrapper" v-show="modalStatus">
            <div class="media-library-inner">
                <span class="media-close-btn" @click="modalClose"><i class="fas fa-times"></i></span>
                <div class="media-modal-nav-menu">
                    <ul class="nav-tabs">
                        <li class="nav-item" :class="tabShow === 'upload' ? 'active' : ''" @click="tabToggle('upload')">
                            Upload Images
                        </li>
                        <li class="nav-item" :class="tabShow === 'images' ? 'active' : ''" @click="tabToggle('images')">
                            All Images
                        </li>
                    </ul>
                </div>
                <div class="media-modal-body">
                    <div class="media-tab upload-tab" v-show="tabShow === 'upload'">
                        <div class="dropzone-form-wrapper">
                            <form :action="route('admin.upload.media.file')" method="post" id="placeholderfForm"
                                  class="dropzone" enctype="multipart/form-data">
                                <input type="hidden" name="_token" :value="csrfToken">
                                <input type="hidden" name="user_type" :value="userType">
                                <div class="dz-default dz-message">
                                    <span>Drag or Select Your Image</span>
                                    <span class="xg-accept-files">Support Formats ( jpg,jpeg,png,gif,pdf,doc,docx,txt,svg,zip,csv,xlsx,xlsm,xlsb,xltx,pptx,pptm,ppt )</span>
                                    <span class="xg-accept-files">Max Upload Size: 200MB</span>
                                    <span class="xg-accept-files">Max Upload Files: 50</span>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="media-tab image-show-tab" v-show="tabShow === 'images'">
                        <div class="media-image-top-part">
                            <div class="media-image-show-wrap">
                                <ul class="media-uploader-image-list">
                                    <li
                                        v-for="image in allImagesList"
                                        :class="image.image_id === currentSelectedImage ? 'selected' : ''"
                                        @click="selectImage(image)"
                                    >
                                        <div class="attachment-preview">
                                            <div class="thumbnail">
                                                <div class="centered">
                                                   <div class="icon-wrap" v-if="['pdf','doc','docx','txt','zip','csv','xlsx','xlsm','xlsb','xltx','pptx','pptm','ppt'].includes(image.type)">
                                                       <i class="fas fa-file file-icon"></i>
                                                       <span class="file-name">{{image.type}}</span>
                                                   </div>
                                                    <img v-else :src="image.img_url" :alt="image.alt">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div class="loadmorewrap" v-show="showLoadMore">
                                    <button @click="loadMoreImage" type="button">LoadMore</button>
                                </div>
                            </div>
                            <div class="media-image-info">
                                <div class="img-wrapper" v-show="selectedImageInfo">
                                    <div class="icon-wrap" v-if="['pdf','doc','docx','txt','zip','csv','xlsx','xlsm','xlsb','xltx','pptx','pptm','ppt'].includes(selectedImageInfo?.type)" >
                                        <i class="fas fa-file fa-2x"></i>
                                        <span class="file-name">{{selectedImageInfo?.type}}</span>
                                    </div>
                                    <img v-else :src="selectedImageInfo?.img_url" :alt="selectedImageInfo?.alt">
                                </div>
                                <div class="img-info" v-show="selectedImageInfo">
                                    <h5 class="img-title">{{ selectedImageInfo?.title }}</h5>
                                    <ul class="img-meta">
                                        <li class="date">{{ selectedImageInfo?.upload_at }}</li>
                                        <li class="dimension">{{ selectedImageInfo?.dimensions }}</li>
                                        <li class="size">{{ selectedImageInfo?.size }}</li>
                                        <li class="imgsrc">{{ selectedImageInfo?.img_url }}</li>
                                        <li class="imgalt">
                                            <div class="img-alt-wrap">
                                                <input type="text" name="img_alt_tag" placeholder="alt"
                                                       :value="selectedImageInfo?.alt" @change="altTextInputChange($event.target.value)">
                                                <button @click="altTextChange(selectedImageInfo)"
                                                        class="btn btn-success img_alt_submit_btn"><i
                                                    class="fas fa-check"></i></button>
                                            </div>
                                        </li>
                                    </ul>
                                    <button tabindex="0" class=" btn btn-lg btn-danger btn-sm mb-3 mr-1"
                                            @click="deleteImage(selectedImageInfo)" type="button">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="media-image-bottom-part" v-show="tabShow === 'images'">
                        <button type="button" class="btn btn-primary" @click="selectedImage">Select Image</button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script>
import {ref, Transition, onMounted, watch} from "vue";
import Dropzone from "dropzone";
import "dropzone/dist/dropzone.css";
import Swal from "sweetalert2";
import axios from "axios";
import {useForm} from "@inertiajs/inertia-vue3"


export default {
    name: "MediaUploader",
    props: {
        buttonText: {
            type: String,
            default: "Upload Image"
        },
        modelValue: {
            type: [String, Number],
            default: ''
        },
        userType: {
            type: String,
            default: 'admin'
        }
    },
    components: {
        Transition
    },
    setup(props, context) {
        const modalStatus = ref(false);
        const tabShow = ref('upload');
        const totalImage = ref(0);
        const currentSelectedImage = ref(0);
        const selectedImageInfo = ref(null);
        const showLoadMore = ref(true);
        const allImagesList = ref([]);
        const getOldImage = ref([]);
        let buttonTextData = ref(props.buttonText);

        function openMediaModal() {
            modalStatus.value = true;
        }

        function modalClose() {
            modalStatus.value = false;
        }

        function tabToggle(tabname) {
            tabShow.value = tabname;
        }

        function loadMoreImage() {
            axios
            .post(route('admin.upload.media.file.loadmore'),{
                skip : allImagesList.value.length
            })
            .then(response => {
                let imageData = response.data;
                if (imageData.length > 1){
                    allImagesList.value.push(response.data.shift());
                }
                showLoadMore.value = imageData.length < 20 ? false : true;
            })
            .catch(error => {
                console.log(error)
            })
        }

        function selectedImage(item) {
            getOldImage.value = selectedImageInfo.value;
            modalStatus.value = false;
            context.emit('update:modelValue',selectedImageInfo.value.image_id);
            buttonTextData.value = 'Change';
        }

        function selectImage(item) {
            selectedImageInfo.value = item;
            currentSelectedImage.value = item.image_id;
        }

        function altTextChange(item)
        {
            const altData = useForm({id:item.image_id,alt:item.alt});
            altData.post(route('admin.upload.media.file.alt.change'),{
                onSuccess: () => {
                    Swal.fire('Alt Text Changed','','success');
                }
            })
        }

        function deleteImage(item) {
            const deleteData = useForm({
                id: item.image_id
            });
            deleteData.post(route('admin.upload.media.file.delete'),{
                onSuccess: () => {
                    Swal.fire('Success!','image deleted from database and storage','warning');
                    allImagesList.value = allImagesList.value.filter((image) => image.image_id !== item.image_id);
                }
            })
        }

        function altTextInputChange(text){
            selectedImageInfo.value.alt = text;
        }
        function removeImage(){
            getOldImage.value = {};
            context.emit('update:modelValue',null);
            buttonTextData.value = 'Upload';
        }
        function allImages() {

            axios
                .post(route('admin.upload.media.file.all'))
                .then((response) => {
                    allImagesList.value = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        function DropzoneSetup() {
            Dropzone.autoDiscover = false;

            let myDropzone = new Dropzone("#placeholderfForm");

            myDropzone.on("success", (file, response) => {
                tabShow.value = "images";
                allImages();
            });

            myDropzone.on("error", (file, message) => {
                let errordata = message.errors;
                tabShow.value = "upload";
                Swal.fire('Error',errordata.file.shift(),'error');
            });

            myDropzone.options.placeholderfForm = {
                dictDefaultMessage: "<div class='alert alert-danger'>{{__('Drag or Select Your Image')}}</div>",
                maxFiles: 50,
                maxFilesize: 200, //MB
                acceptedFiles: 'image/*,application/pdf,.doc,.docx,.txt,.svg,.zip,.csv,.xlsx,.xlsm,.xlsb,.xltx,.pptx,.pptm,.ppt',
            };
        }

        function fetchSingleImage(){
            if (props.modelValue != null){
                axios
                .post(route('admin.upload.media.file.single'),{id: props.modelValue})
                .then( response => {
                    getOldImage.value = response.data;
                    buttonTextData.value = 'Change';
                });
            }
        }
        watch(() => props.modelValue,(newValue) => {
            if (newValue === null){
                removeImage();
            }
        });
        onMounted(allImages);
        onMounted(DropzoneSetup);
        onMounted(fetchSingleImage);

        return {
            modalStatus,
            tabShow,
            currentSelectedImage,
            selectedImageInfo,
            csrfToken,
            allImagesList,
            showLoadMore,
            getOldImage,
            buttonTextData,
            openMediaModal,
            modalClose,
            tabToggle,
            loadMoreImage,
            selectedImage,
            allImages,
            selectImage,
            altTextChange,
            deleteImage,
            altTextInputChange,
            removeImage
        }
    }
}
</script>
