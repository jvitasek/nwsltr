<template>
    <section :class="['composer', previewDesktop ? 'composer--desktop' : '', previewMobile ? 'composer--mobile' : '', previewTablet ? 'composer--tablet' : '']">
        <div class="composer__header">
            <div class="d-flex align-items-center">
                <button type="button" 
                    :class="sidebarComponents ? 'tools-item active ms-0' : 'tools-item ms-0'"
                    v-on:click="toggleSidebar('components')"
                >
                    <svg class="icon"><title>Add Component</title><use xlink:href="#plus"></use></svg>
                </button>
                <button type="button"
                    :class="sidebarArticle ? 'tools-item active' : 'tools-item'"
                    v-on:click="toggleSidebar('article')"
                >
                    <svg class="icon"><title>Mailing</title><use xlink:href="#edit"></use></svg>
                </button>
                <button type="button"
                    :class="sidebarHelp ? 'tools-item active' : 'tools-item'"
                    v-on:click="toggleSidebar('help')"
                >
                    <svg class="icon"><title>Help</title><use xlink:href="#help"></use></svg>
                </button>
                <button type="button"
                    :class="sidebarView ? 'tools-item active' : 'tools-item'"
                    v-on:click="toggleSidebar('view')"
                >
                    <svg class="icon"><title>Help</title><use xlink:href="#devices"></use></svg>
                </button>
            </div>
            <div class="d-flex align-items-center">
                <a :href="backButton" class="btn btn-primary me-3">Back</a>
                <button type="button" v-on:click="send(true)" class="btn btn-secondary me-3">Preview</button>
                <button type="button" class="btn btn-tertiary" v-on:click="send(false)">Save</button>
            </div>
        </div>

        <div class="composer-main-wrapper">
            <div class="composer__sidebar composer__sidebar--components" v-if="sidebarComponents">
                <div class="components-wrapper">
                    <div class="item">
                        <div class="dropdown">
                            <button class="btn-add" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg class="icon"><use xlink:href="#heading"></use></svg>
                                <div class="heading">Heading</div>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div class="dropdown-menu-wrapper">
                                    <button v-on:click="addHeading(1)" class="dropdown-item">Heading Level 1</button>
                                    <button v-on:click="addHeading(2)" class="dropdown-item">Heading Level 2</button>
                                    <button v-on:click="addHeading(3)" class="dropdown-item">Heading Level 3</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <button class="btn-add" type="button" v-on:click="addParagraph('text')">
                            <svg class="icon"><use xlink:href="#paragraph"></use></svg>
                            <div class="heading">Paragraph</div>
                        </button>
                    </div>
                    <div class="item">
                        <button class="btn-add" type="button" v-on:click="addQuotation">
                            <svg class="icon"><use xlink:href="#quote"></use></svg>
                            <div class="heading">Quote</div>
                        </button>
                    </div>
                    <div class="item">
                        <div class="dropdown">
                            <button class="btn-add" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg class="icon"><use xlink:href="#list"></use></svg>
                                <div class="heading">List</div>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div class="dropdown-menu-wrapper">
                                    <button v-on:click="addList('ul')" class="dropdown-item">Unordered</button>
                                    <button v-on:click="addList('ol')" class="dropdown-item">Ordered</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <button class="btn-add" type="button" v-on:click="addGallery">
                            <svg class="icon"><use xlink:href="#image"></use></svg>
                            <div class="heading">Gallery</div>
                        </button>
                    </div>
                    <div class="item">
                        <button class="btn-add" type="button" v-on:click="addButton()">
                            <svg class="icon"><use xlink:href="#link"></use></svg>
                            <div class="heading">Button</div>
                        </button>
                    </div>
                    <div class="item">
                        <button class="btn-add" type="button" v-on:click="addDivider()">
                            <svg class="icon"><use xlink:href="#divider"></use></svg>
                            <div class="heading">Divider</div>
                        </button>
                    </div>
                    <div class="item">
                        <button class="btn-add" type="button" v-on:click="addSpacer()">
                            <svg class="icon"><use xlink:href="#spacer"></use></svg>
                            <div class="heading">Spacer</div>
                        </button>
                    </div>
                    <div class="item">
                        <button class="btn-add" type="button" v-on:click="addHtml()">
                            <svg class="icon"><use xlink:href="#code"></use></svg>
                            <div class="heading">HTML</div>
                        </button>
                    </div>
                </div>
            </div>
            <div class="composer__sidebar composer__sidebar--form" v-if="sidebarArticle">
                <div class="form-group">
                    <label for="">Mailing Title</label>
                    <input v-model="campaignMeta.title" type="text">
                </div>
                <div class="form-group">
                    <label for="">E-mail Subject</label>
                    <input v-model="campaignMeta.subject" type="text">
                </div>
              <div class="form-group">
                <label for="">E-mail From</label>
                <input v-model="campaignMeta.emailFrom" type="text">
              </div>
                <div class="form-group">
                    <label for="">Sendout Date</label>
                    <div class="custom-datepicker">
                    <input type="text" name="date" id="date" placeholder="Select Sendout Date" v-model="campaignMeta.date">
                        <svg class="icon"><use xlink:href="#calendar"></use></svg>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Recipients</label>
                    <select name="" id="select-1" multiple v-model="recipients">
                        <option v-for="recipient in recipientGroups" :key="recipient.id" :value="recipient.id">{{ recipient.title }}</option>                        
                    </select>
                </div>
            </div>
            <div class="composer__sidebar composer__sidebar--help" v-if="sidebarHelp">
                <ul class="article-info">
                    <li><span class="heading">Components</span><span class="value">{{ $store.getters.getComposer.body.length }}</span></li>
                    <li><span class="heading">Headings</span><span class="value">{{ headingsCounter }}</span></li>
                    <li><span class="heading">Paragraphs</span><span class="value">{{ paragraphsCounter }}</span></li>
                    <li><span class="heading">Words</span><span class="value">{{ contentCounter.words }}</span></li>
                    <li><span class="heading">Characters</span><span class="value">{{ contentCounter.chars }}</span></li>
                </ul>
                <article class="message message--error" v-if="!campaignMeta.title.length > 0">
                    <svg class="icon"><use xlink:href="#warn"></use></svg>
                    Enter Mailing Title
                </article>
                <article class="message message--error" v-if="!campaignMeta.subject.length > 0">
                    <svg class="icon"><use xlink:href="#warn"></use></svg>
                    Enter E-mail Subject
                </article>
                <article class="message message--error" v-if="!campaignMeta.date.length > 0">
                    <svg class="icon"><use xlink:href="#warn"></use></svg>
                    Enter Sendout Date
                </article>
            </div>
            <div class="composer__sidebar composer__sidebar--components" v-if="sidebarView">
                <div class="components-wrapper">
                    <div class="item">
                        <button class="btn-add" type="button" v-on:click="setView('desktop')">
                            <svg class="icon"><use xlink:href="#desktop"></use></svg>
                            <div class="heading">Desktop</div>
                        </button>
                    </div>
                    <div class="item">
                        <button class="btn-add" type="button" v-on:click="setView('tablet')">
                            <svg class="icon"><use xlink:href="#tablet"></use></svg>
                            <div class="heading">Tablet</div>
                        </button>
                    </div>
                    <div class="item">
                        <button class="btn-add" type="button" v-on:click="setView('mobile')">
                            <svg class="icon"><use xlink:href="#mobile"></use></svg>
                            <div class="heading">Mobile</div>
                        </button>
                    </div>
                </div>
            </div>

            <div :class="['composer-body-wrapper', view === 'mobile' ? 'composer-body-wrapper--mobile' : '', view === 'tablet' ? 'composer-body-wrapper--tablet' : '']">
                <div class="composer-body">
                    <div class="composer-wrapper">
                        <template v-for="component in $store.getters.getComposer.body">
                            <component :is="component.component" :component="component" :key="component.id"></component>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template> 

<script>
    import flatpickr from "flatpickr";
    import axios from 'axios';
    import Choices from 'choices.js';

    import Heading from "./components/Heading";
    import Gallery from "./components/Gallery";
    import Paragraph from "./components/Paragraph";
    import Quotation from "./components/Quotation";
    import List from "./components/List";
    import Button from "./components/Button";
    import Divider from "./components/Divider";
    import Spacer from "./components/Spacer";
    import Html from "./components/Html";

    export default {
        props: ['backButton', 'previewButton', 'id'],
        data() {
            return {
                sidebarComponents: false,
                sidebarHelp: false,
                sidebarView: false,
                sidebarArticle: false,
                sidebarDevices: false,
                previewDesktop: true,
                previewMobile: false,
                previewTablet: false,
                recipientGroups: [],
                view: 'desktop',
                composerDefaultState: {
                    id: this.id,
                    recipientGroups: [],
                    meta: {
                        title: '',
                        subject: '',
                        emailFrom: '',
                        date: ''
                    },
                    lastComponentId: '1',
                    body: []
                }
            };
        },
        computed: {
            campaignMeta: {
                get() {
                    return this.$store.getters.getCampaignMeta
                },
                set(payload) {
                    this.$store.commit('updateMeta', payload)
                }
            }, 
            composerBody: {
                get() {
                    return this.$store.getters.getComposer.body
                },
                set(composerBody) {
                    this.$store.commit('setComposerBody', composerBody)
                }
            },
            recipients: {
                get() {
                    return this.$store.getters.getComposer.recipientGroups
                },
                set(recipients) {
                    this.$store.commit('setComposerRecipients', recipients)
                }
            },
            headingsCounter() {
                return this.$store.getters.getComposer.body.filter(item => { return item.component === 'Heading' }).length
            },
            paragraphsCounter() {
                return this.$store.getters.getComposer.body.filter(item => { return item.component === 'Paragraph' }).length
            },
            contentCounter() {
                let chars = 0, words = 0
                let headings = this.$store.getters.getComposer.body.filter(item => { return item.component === 'Heading' })
                let paragraphs = this.$store.getters.getComposer.body.filter(item => { return item.component === 'Paragraph' })
                let quotations = this.$store.getters.getComposer.body.filter(item => { return item.component === 'Quotation' })
                let lists = this.$store.getters.getComposer.body.filter(item => { return item.component === 'List' })

                headings.forEach(heading => {
                    chars += this.stripHtml(heading.content).length
                    words += this.countWords(this.stripHtml(heading.content))
                })
                paragraphs.forEach(paragraph => {
                    chars += this.stripHtml(paragraph.content).length
                    words += this.countWords(this.stripHtml(paragraph.content))
                })
                quotations.forEach(quotation => {
                    chars += this.stripHtml(quotation.content).length
                    words += this.countWords(this.stripHtml(quotation.content))
                })
                lists.forEach(list => {
                    if(list.items.length > 0) {
                        list.items.forEach(listItem => {
                            chars += this.stripHtml(listItem).length
                            words += this.countWords(this.stripHtml(listItem))
                        })
                    }
                })

                return {chars, words}
            },
            requiredInputs() {
                let mainHeading = false, mainImage = false, perex = false, articleAuthor = false, articleDate = false, articleCategory = false

                if(this.$store.getters.getComposer.body[0]) {
                    if(this.$store.getters.getComposer.body[0].content.length > 0) {
                        mainHeading = true
                    }
                }

                if(this.$store.getters.getComposer.body[1]) {
                    if(this.$store.getters.getComposer.body[1].images.length > 0) {
                        mainImage = true
                    }
                }

                if(this.$store.getters.getComposer.body[2]) {
                    if(this.$store.getters.getComposer.body[2].content.length > 0) {
                        perex = true
                    }
                }

                return {mainHeading, mainImage, perex}
            }
        },
        methods: {
            addHeading(headingType) {
                this.$store.commit('setComposerlastComponentId', parseInt(this.$store.getters.getComposerlastComponentId)+1)
                let newItem = {
                    id: this.$store.getters.getComposerlastComponentId,
                    component: 'Heading',
                    content: '',
                    headingType: headingType,
                    required: false
                }
                this.$store.commit('pushComposerItem', newItem)
                this.$nextTick(() => {
                    document.querySelector('#input-'+newItem.id).focus()
                });
            },
            addGallery() {
                this.$store.commit('setComposerlastComponentId', parseInt(this.$store.getters.getComposerlastComponentId)+1)
                let newItem = {
                    id: this.$store.getters.getComposerlastComponentId,
                    component: 'Gallery',
                    images: [],
                    required: false
                }
                this.$store.commit('pushComposerItem', newItem)
                this.$nextTick(() => {
                    document.querySelector('#input-'+newItem.id).focus()
                });
            },
            addParagraph(paragraphType) {
                this.$store.commit('setComposerlastComponentId', parseInt(this.$store.getters.getComposerlastComponentId)+1)
                let newItem = {
                    id: this.$store.getters.getComposerlastComponentId,
                    component: 'Paragraph',
                    paragraphType: paragraphType,
                    content: '',
                    required: false
                }
                this.$store.commit('pushComposerItem', newItem)
                this.$nextTick(() => { 
                    document.querySelector('#input-'+newItem.id).focus()
                    document.querySelector('#input-'+newItem.id).value = ''
                }); 
            },
            addQuotation() {
                this.$store.commit('setComposerlastComponentId', parseInt(this.$store.getters.getComposerlastComponentId)+1)
                let newItem = {
                    id: this.$store.getters.getComposerlastComponentId,
                    component: 'Quotation',
                    content: '',
                    required: false
                }
                this.$store.commit('pushComposerItem', newItem)
                this.$nextTick(() => {
                    document.querySelector('#input-'+newItem.id).focus()
                }); 
            },
            addList(listType) {
                this.$store.commit('setComposerlastComponentId', parseInt(this.$store.getters.getComposerlastComponentId)+1)
                let newItem = {
                    id: this.$store.getters.getComposerlastComponentId,
                    component: 'List',
                    listType: listType,
                    items: [''],
                    required: false
                }
                this.$store.commit('pushComposerItem', newItem)
                this.$nextTick(() => {
                    document.querySelector('#input-item-'+newItem.id+'-0').focus()
                }); 
            },
            addButton() {
                this.$store.commit('setComposerlastComponentId', parseInt(this.$store.getters.getComposerlastComponentId)+1)
                let newItem = {
                    id: this.$store.getters.getComposerlastComponentId,
                    component: 'Button',
                    content: '',
                    link: '',
                    newsletterLink: '',
                    required: false
                }
                this.$store.commit('pushComposerItem', newItem)
                this.$nextTick(() => {
                    document.querySelector('#input-'+newItem.id).focus()
                });
            },
            addDivider() {
                this.$store.commit('setComposerlastComponentId', parseInt(this.$store.getters.getComposerlastComponentId)+1)
                let newItem = {
                    id: this.$store.getters.getComposerlastComponentId,
                    component: 'Divider',
                    required: false
                }
                this.$store.commit('pushComposerItem', newItem)
            },
            addSpacer() {
                this.$store.commit('setComposerlastComponentId', parseInt(this.$store.getters.getComposerlastComponentId)+1)
                let newItem = {
                    id: this.$store.getters.getComposerlastComponentId,
                    component: 'Spacer',
                    required: false
                }
                this.$store.commit('pushComposerItem', newItem)
            },
            addHtml() {
                this.$store.commit('setComposerlastComponentId', parseInt(this.$store.getters.getComposerlastComponentId)+1)
                let newItem = {
                    id: this.$store.getters.getComposerlastComponentId,
                    component: 'Html',
                    content: '',
                    required: false
                }
                this.$store.commit('pushComposerItem', newItem)
            },
            send(showPreview) {
                const config = {
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    }
                }

                const url = '/api/editor/save'

                axios.post(url, this.$store.getters.getComposer, config)
                    .then((result) => {
                        console.log(result)
                        if(result.data.result === 'ok') {
                            this.$toasted.success('Mailing saved successfully')
                            if(showPreview) {
                                console.log('redir')
                                window.location.href = this.previewButton
                            }
                        }
                        else {
                            this.$toasted.error('There was an error while saving the record')
                        }
                    })
                    .catch((err) => {
                        console.log(err)
                        this.$toasted.error('There was an error while saving the record')
                    })
            },
            fetchData() {
                const url = `/api/editor/read/${this.id}`
                axios
                    .get(url)
                    .then(response => {
                        this.$store.commit('setComposer', this.composerDefaultState) 
                        console.log(response.data)

                        if(response.data && response.data.length !== 0) { 
                            if(response.data.body && response.data.body.length > 0) {
                                this.composerBody = response.data.body
                                this.$store.commit('setComposerlastComponentId', parseInt(response.data.lastComponentId))
                            }
                            if(response.data.meta) {
                                this.campaignMeta.title = response.data.meta.title
                                this.campaignMeta.date = response.data.meta.date
                                this.campaignMeta.subject = response.data.meta.subject
                                this.campaignMeta.emailFrom = response.data.meta.emailFrom
                            }
                        }
                    })
                    .catch(error => {
                        console.log(error)
                    })
            },
            fetchRecipients() {
                axios
                    .get(`/api/recipient-group/read/${this.id}`)
                    .then(response => {
                        this.recipientGroups = response.data
                    })
                    .catch(error => {
                        console.log(error) 
                    })

                axios
                    .get(`/api/selected-recipient-group/read/${this.id}`)
                    .then(response => {
                        this.recipients = []
                        response.data.forEach(item => {
                            this.recipients.push(item.id)
                        })
                        console.log(this.recipients)
                    })
                    .catch(error => {
                        console.log(error)
                    })
            },
            toggleSidebar(sidebar) {
                if(sidebar === 'components') {
                    this.sidebarComponents = !this.sidebarComponents
                    this.sidebarHelp = false
                    this.sidebarArticle = false
                    this.sidebarDevices = false
                    this.sidebarView = false
                }
                else if(sidebar === 'article') {
                    this.sidebarComponents = false
                    this.sidebarHelp = false
                    this.sidebarArticle = !this.sidebarArticle
                    this.sidebarDevices = false
                    this.sidebarView = false

                    this.initFlatpickr()
                    this.initChoices()
                }
                else if(sidebar === 'help') {
                    this.sidebarComponents = false
                    this.sidebarHelp = !this.sidebarHelp
                    this.sidebarArticle = false
                    this.sidebarDevices = false
                    this.sidebarView = false
                }
                else if(sidebar === 'devices') {
                    this.sidebarComponents = false
                    this.sidebarHelp = false
                    this.sidebarArticle = false
                    this.sidebarDevices = !this.sidebarDevices
                    this.sidebarView = false
                }
                else if(sidebar === 'view') {
                    this.sidebarComponents = false
                    this.sidebarHelp = false
                    this.sidebarArticle = false
                    this.sidebarDevices = false
                    this.sidebarView = !this.sidebarView
                }
            },
            toggleDevice(device) {
                if(device === 'desktop') {
                    this.previewDesktop = true
                    this.previewMobile = false
                    this.previewTablet = false
                }
                else if(device === 'mobile') {
                    this.previewDesktop = false
                    this.previewMobile = true
                    this.previewTablet = false
                }
                else if(device === 'tablet') {
                    this.previewDesktop = false
                    this.previewMobile = false
                    this.previewTablet = true
                }
            },
            setView(view) {
                this.view = view
            },
            stripHtml(html) {
                let tmp = document.createElement("DIV");
                tmp.innerHTML = html;
                return tmp.textContent || tmp.innerText || "";
            },
            countWords(s){
                s = s.replace(/(^\s*)|(\s*$)/gi,"");
                s = s.replace(/[ ]{2,}/gi," ");
                s = s.replace(/\n /,"\n");
                return s.split(' ').filter(function(str){return str!="";}).length;
            },
            initFlatpickr() {
                this.$nextTick(() => {
                    document.querySelectorAll('.custom-datepicker').forEach(customDatepicker => {
                        flatpickr(customDatepicker.querySelector('input'), {
                            enableTime: true,
                            dateFormat: 'Y-m-d G:i:S'
                        });
                    })
                });
            },
            initChoices() {
                this.$nextTick(() => {
                    document.querySelectorAll('select[multiple]').forEach(multiSelect => { 
                        new Choices(multiSelect, {
                            removeItemButton: true,
                            loadingText: 'Loading...',
                            noResultsText: 'No results',
                            noChoicesText: 'No choices',
                            itemSelectText: 'Click for selection',
                            addItemText: 'Press enter to add',
                            maxItemText: 'Only ${maxItemCount} items can be added',
                            searchPlaceholderValue: 'Search...'
                        });
                    })
                });
            }
        },
        components: {
            Heading,
            Gallery,
            Paragraph,
            Quotation,
            List,
            Button,
            Divider,
            Spacer,
            Html
        },
        mounted() {
            this.$nextTick(() => { 
                this.fetchData()
                this.fetchRecipients()
            })
        } 
    }
</script>
