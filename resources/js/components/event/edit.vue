<template>
    <div>
        <v-toolbar flat color="white" class="mb-3">
            <v-toolbar-title class="text-uppercase title">add event</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn color="teal" tile @click="submit" class="mr-2 custom_button">
                <v-icon left>mdi-content-save</v-icon>
            Create Event
            </v-btn>
            <v-btn color="primary" tile @click="$router.go(-1)">
                <v-icon left>mdi-arrow-left-circle</v-icon>
                     back
            </v-btn>
        </v-toolbar>
        <v-container grid-list-md>
            <v-layout row wrap>
                <v-flex xs12 sm9>
                    <v-stepper v-model="e1">
                        <v-stepper-header>
                            <v-stepper-step :complete="e1 > 1" step="1" editable>Event Details and Pricing</v-stepper-step>
                            <v-divider></v-divider>
                            <v-stepper-step :complete="e1 > 2" step="2" editable>Programs and Presentation</v-stepper-step>
                            <v-divider></v-divider>
                            <v-stepper-step :complete="e1 > 3" step="3" editable>Event Images</v-stepper-step>
                            <v-divider></v-divider>
                            <v-stepper-step :complete="e1 > 4" step="4" editable>Event Map</v-stepper-step>
                        </v-stepper-header>
                        <v-stepper-items>
                            <v-stepper-content step="1"> <!-- step 1 -->
                                <v-form ref="seller_form">
                                    <v-container  grid-list-md>
                                        <v-layout row wrap>
                                            <v-flex xs12 md12>
                                                    <v-layout row wrap mb-3 class="text-capitalize">
                                                        <v-flex xs12>
                                                            <p class="subheading pa-0 font-weight-bold">Event Details</p>
                                                        </v-flex>
                                                        <v-flex xs12 sm6>
                                                            <v-text-field 
                                                                type="text"
                                                                v-model="event.name"
                                                                v-validate="'required|min:3'" 
                                                                :error-messages="errors.collect('Event Name')" 
                                                                data-vv-name="Event Name" 
                                                                label="Event Name" required>
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12 sm6>
                                                            <v-text-field 
                                                                v-model="event.place"
                                                                type="text"
                                                                label="Event Place" required>
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-menu
                                                                ref="menu"
                                                                v-model="menu"
                                                                :close-on-content-click="false"
                                                                transition="scale-transition"
                                                                offset-y
                                                                min-width="290px"
                                                            >
                                                                <template v-slot:activator="{ on }">
                                                                <v-text-field
                                                                   v-model="dateRangeText"
                                                                    label="event date range"
                                                                    readonly
                                                                    v-on="on"
                                                                ></v-text-field>
                                                                </template>
                                                                <v-date-picker  v-model="event.dates" range  :min="min_date">
                                                                <v-spacer></v-spacer>
                                                                <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                                                                <v-btn text color="primary" @click="$refs.menu.save(event.dates)">OK</v-btn>
                                                                </v-date-picker>
                                                            </v-menu>
                                                        </v-flex>
                                                        <v-flex xs12 sm12>
                                                            <div class="subheading pa-0 mb-2 font-weight-bold">Event Description</div>
                                                            <text-editor v-model="event.description"></text-editor>
                                                        </v-flex>
                                                    </v-layout>
                                            </v-flex>
                                            <v-flex xs12>
                                                <p class="subheading pa-0 font-weight-bold">Event Pricing</p>
                                            </v-flex>
                                            <v-flex xs12  >
                                                <v-select
                                                    :items="categories"
                                                    v-model="event.prices"
                                                    item-text="name"
                                                    item-value="id"
                                                    outlined
                                                    multiple
                                                    dense
                                                    :menu-props="{ maxHeight: '400' }"
                                                    return-object
                                                    label="Age Categories"
                                                ></v-select>
                                                    <v-container>
                                                        <v-layout row wrap v-if="event.prices.length">
                                                            <v-flex sm12 md3  v-for="(item, index) in event.prices" :key="index" class="pr-4" >
                                                                <transition name="fade">
                                                                    <v-text-field
                                                                        dense
                                                                        type="number"
                                                                        append-icon="mdi-currency-eur"
                                                                        v-model="item.price"
                                                                        :label="item.name"
                                                                        outlined left
                                                                    ></v-text-field>
                                                                </transition>
                                                            </v-flex>
                                                        </v-layout>
                                                        <v-alert dense outlined type="info" v-else >
                                                    No Prices added yet .
                                                </v-alert>
                                                    </v-container>
                                            </v-flex>
                                        
                                        </v-layout>
                                    </v-container>
                                </v-form>
                                <v-btn class="float-right" color="primary" @click.native="e1 = 2">Continue</v-btn>
                            </v-stepper-content>
                            <v-stepper-content step="2"> <!-- step 2 -->
                                <v-form ref="seller_form">
                                    <v-container  grid-list-md>
                                        <v-layout row wrap>
                                            <v-flex xs12 md12>
                                                    <v-layout row wrap mb-3 class="text-capitalize">
                                                        <v-flex xs12>
                                                                <v-btn small color="success" class="float-right" dense @click="add_programs">
                                                                    <v-icon left>mdi-plus</v-icon> add programs
                                                                </v-btn>
                                                                <p class="subheading font-weight-bold">Event Programs</p>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <div v-if="event.programs.length"  d-flex>
                                                                <v-expansion-panels >
                                                                    <v-expansion-panel
                                                                    v-for="(item,index) in event.programs"
                                                                    :key="index"
                                                                    >
                                                                    <v-expansion-panel-header class=" py-4 font-weight-bold" >{{item.name ? item.name : 'Click to add details'}} {{item.time}}
                                                                        <div>
                                                                             <v-btn icon color="error"  @click.native.stop="remove_program(index)" class="float-right" right="">
                                                                                        <v-icon>mdi-delete</v-icon>
                                                                                    </v-btn>
                                                                            <v-tooltip bottom>
                                                                                <template v-slot:activator="{ on }">
                                                                                    <v-btn icon dark v-on="on" :color="item.highlight ? 'success' : 'warning'" @click.native.stop="item.highlight = !item.highlight" class="float-right" right="">
                                                                                <v-icon>mdi-star-circle-outline</v-icon>
                                                                            </v-btn>
                                                                                </template>
                                                                                <span>{{item.highlight ? 'Highlighted' : 'Highlight'}}</span>
                                                                                </v-tooltip>
                                                                        </div>
                                                                    </v-expansion-panel-header>
                                                                    <v-divider></v-divider>
                                                                    <v-expansion-panel-content>
                                                                        <v-container grid-list-md>
                                                                            <v-layout row wrap>
                                                                                <v-flex xs12 sm6>
                                                                                    <v-text-field
                                                                                        v-model="item.name"
                                                                                        label="program name"
                                                                                    ></v-text-field>
                                                                                </v-flex>
                                                                                <v-flex xs12 sm6>
                                                                                    <v-menu
                                                                                        v-model="item.model"
                                                                                        :close-on-content-click="false"
                                                                                        transition="scale-transition"
                                                                                        offset-y
                                                                                        min-width="290px"
                                                                                    >
                                                                                        <template v-slot:activator="{ on }">
                                                                                        <v-text-field
                                                                                        v-model="item.time"
                                                                                            label="Time"
                                                                                            readonly
                                                                                            v-on="on"
                                                                                        ></v-text-field>
                                                                                        </template>
                                                                                        <v-time-picker
                                                                                            v-model="item.time"
                                                                                            full-width
                                                                                            format="24hr"
                                                                                            scrollable
                                                                                            @click:minute="$refs.menu.save(item.time) "
                                                                                            ></v-time-picker>
                                                                                    </v-menu>
                                                                                </v-flex>
                                                                                <v-flex xs12>
                                                                                    <v-flex xs12 >
                                                                                        <p class="subheading pa-0 font-weight-bold">Program Details</p>
                                                                                    </v-flex>
                                                                                    <text-editor v-model="item.details"></text-editor>
                                                                                </v-flex>
                                                                            </v-layout>
                                                                        </v-container>
                                                                    </v-expansion-panel-content>
                                                                    </v-expansion-panel>
                                                                </v-expansion-panels>
                                                            </div>
                                                             <v-alert  v-else dense outlined type="info" >
                                                                No Program added yet .
                                                            </v-alert>
                                                        </v-flex>
                                                        <v-flex xs12 class="mt-4">
                                                            <p class="subheading pa-0 font-weight-bold">Event Presentation</p>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-file-input @click:clear="img_remove($event,'present_image')" accept="image/*" label="Presentation Image"  @change="onFileChange($event,'present_image')"></v-file-input>
                                                        </v-flex>
                                                        <v-flex xs12 sm12>
                                                            <div class="subheading pa-0 mb-2 font-weight-bold"> Details</div>
                                                            <text-editor v-model="event.presentation.details"></text-editor>
                                                        </v-flex>
                                                    </v-layout>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-form>
                                <v-btn color="primary" @click.native="e1 = 3" class="float-right">Continue</v-btn>
                            </v-stepper-content>
                            <v-stepper-content step="3"> <!-- step 3 -->
                                    <v-flex xs12 class="mt-4">
                                        <v-btn small color="success" class="float-right" dense @click="use_default">
                                            <v-icon left>mdi-cog-clockwise</v-icon>{{default_image ? 'use default' : 'add images'}}
                                        </v-btn>
                                        <p class="subheading pa-0 font-weight-bold">Event Images</p>
                                    </v-flex>
                                    <v-flex xs12 v-if="default_image">
                                        <v-simple-table light dense >
                                        <template v-slot:default>
                                            <thead>
                                                <tr>
                                                    <th class="text-left" >Image Name</th>
                                                    <th class="text-left">Input Image</th>
                                                    <th class="text-left">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Event Image</td>
                                                    <td class="pt-2">
                                                        <v-file-input  @click:clear="img_remove($event,'event_image')" outlined dense style="width:400px;"  accept="image/*"   @change="onFileChange($event,'event_image')"></v-file-input>
                                                    </td>
                                                    <td>
                                                        <v-icon color="primary" @click="image_preview($event)" :data-image="event.image">mdi-eye</v-icon>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Event Logo</td>
                                                    <td class="pt-2">
                                                        <v-file-input @click:clear="img_remove($event,'event_logo')" outlined dense style="width:400px;"  accept="image/*"   @change="onFileChange($event,'event_logo')"></v-file-input>
                                                    </td>
                                                    <td>
                                                        <v-icon color="primary" @click="image_preview($event)" :data-image="event.logo">mdi-eye</v-icon>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Presentation Image</td>
                                                    <td class="pt-2">
                                                        <v-file-input  @click:clear="img_remove($event,'presentation_image')" outlined dense style="width:400px;"  accept="image/*"   @change="onFileChange($event,'presentation_image')"></v-file-input>
                                                    </td>
                                                    <td>
                                                        <v-icon color="primary" @click="image_preview($event)" :data-image="event.presentation_image">mdi-eye</v-icon>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Programme Image</td>
                                                    <td class="pt-2">
                                                        <v-file-input @click:clear="img_remove($event,'programme_image')" outlined dense style="width:400px;"  accept="image/*"   @change="onFileChange($event,'programme_image')"></v-file-input>
                                                    </td>
                                                    <td>
                                                        <v-icon color="primary" @click="image_preview($event)" :data-image="event.programme_image">mdi-eye</v-icon>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tarifs Image</td>
                                                    <td class="pt-2">
                                                        <v-file-input @click:clear="img_remove($event,'tarifs_image')" outlined dense style="width:400px;"  accept="image/*"   @change="onFileChange($event,'tarifs_image')"></v-file-input>
                                                    </td>
                                                    <td>
                                                        <v-icon color="primary" @click="image_preview($event)" :data-image="event.tarifs_image">mdi-eye</v-icon>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Plan Image</td>
                                                    <td class="pt-2">
                                                        <v-file-input @click:clear="img_remove($event,'plan_image')" outlined dense style="width:400px;"  accept="image/*"   @change="onFileChange($event,'plan_image')"></v-file-input>
                                                    </td>
                                                    <td>
                                                        <v-icon color="primary" @click="image_preview($event)" :data-image="event.plan_image">mdi-eye</v-icon>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Restaurant Image</td>
                                                    <td class="pt-2">
                                                        <v-file-input outlined dense style="width:400px;"  accept="image/*"   @change="onFileChange($event,'restaurant_image')"></v-file-input>
                                                    </td>
                                                    <td>
                                                        <v-icon color="primary" @click="image_preview($event)" :data-image="event.restaurant_image">mdi-eye</v-icon>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Highlight Image</td>
                                                    <td class="pt-2">
                                                        <v-file-input @click:clear="img_remove($event,'highlights_image')" outlined dense style="width:400px;"  accept="image/*"   @change="onFileChange($event,'highlights_image')"></v-file-input>
                                                    </td>
                                                    <td>
                                                        <v-icon color="primary" @click="image_preview($event)" :data-image="event.highlights_image">mdi-eye</v-icon>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </template>
                                        </v-simple-table>
                                     <v-dialog
                                        v-model="dialog"
                                        max-width="400"
                                        >
                                        <v-card>
                                            <v-card-title class="headline">Image Preview</v-card-title>

                                            <v-card-text>
                                                <v-img :src="img_preview ? img_preview : 'http://www.aclcbutuan.edu.ph/plugins/images/no-image.jpg'" cover lazy-src="https://media1.tenor.com/images/a6a6686cbddb3e99a5f0b60a829effb3/tenor.gif?itemid=7427055"></v-img>
                                            </v-card-text>

                                            <v-card-actions>
                                            <v-spacer></v-spacer>

                                            <v-btn
                                                color="green darken-1"
                                                text
                                                @click="dialog = false"
                                            >
                                                Okay
                                            </v-btn>

                                            <v-btn
                                                color="green darken-1"
                                                text
                                                @click="dialog = false"
                                            >
                                                Cancel
                                            </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                        </v-dialog>
                                    </v-flex>
                                    <v-flex xs12 v-else>
                                        <v-alert type="info" outlined dense>
                                            Default images will be used .
                                        </v-alert>
                                    </v-flex>
                                <v-btn color="primary" @click.native="e1 = 4" class="float-right mt-3">Continue</v-btn>
                            </v-stepper-content>
                            <v-stepper-content step="4"> <!-- step 4 -->
                                <v-container grid-list-md>
                                    <v-layout row >
                                        <v-flex xs7>
                                           <v-btn small color="success" class="float-right" dense @click="open_map">
                                                <v-icon left>mdi-map-check-outline</v-icon> manage map
                                            </v-btn>
                                            <p class="subheading font-weight-bold">Map Overview</p>
                                            <v-img class="elevation-1" :src="event.map ? event.map : 'http://www.aclcbutuan.edu.ph/plugins/images/no-image.jpg'" contain aspect-ratio="1.5"></v-img>
                                        </v-flex>
                                        <v-flex xs5 class="">
                                            <p class="subheading font-weight-bold">Companies</p>
                                            <v-select
                                                :items="companies"
                                                v-model="event.companies"
                                                item-text="name"
                                                item-value="id"
                                                outlined
                                                multiple
                                                dense
                                                :menu-props="{ maxHeight: '400' }"
                                                return-object
                                                label="Companies"
                                            ></v-select>
                                            <div v-if="event.companies.length"  d-flex>
                                                <v-expansion-panels >
                                                    <v-expansion-panel
                                                    v-for="(item,index) in event.companies"
                                                    :key="index"
                                                    >
                                                    <v-expansion-panel-header class=" py-4 font-weight-bold" >{{item.name ? item.name : 'Click to add details'}}
                                                        <div>
                                                            <v-btn icon color="error"  @click.native.stop="remove_company(index)" class="float-right" right="">
                                                                    <v-icon>mdi-delete</v-icon>
                                                                </v-btn>
                                                            <v-tooltip bottom>
                                                                <template v-slot:activator="{ on }">
                                                                    <v-btn icon dark v-on="on" :color="item.highlight ? 'success' : 'warning'" @click.native.stop="item.highlight = !item.highlight" class="float-right" right="">
                                                                        <v-icon>mdi-star-circle-outline</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>{{item.highlight ? 'removed to highlight' : 'add to highlight'}}</span>
                                                            </v-tooltip>
                                                            <v-tooltip bottom>
                                                                <template v-slot:activator="{ on }">
                                                                    <v-btn icon dark v-on="on" :color="item.is_restaurant ? 'success' : 'warning'" @click.native.stop="item.is_restaurant = !item.is_restaurant" class="float-right" right="">
                                                                        <v-icon>mdi-food</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>{{item.is_restaurant ? 'removed as a restaurant' : 'add as a restaurant'}}</span>
                                                            </v-tooltip>
                                                            
                                                        </div>
                                                    </v-expansion-panel-header>
                                                    <v-divider></v-divider>
                                                    <v-expansion-panel-content>
                                                        <v-container grid-list-md>
                                                            <v-layout row wrap>
                                                                <v-flex xs12>
                                                                    <v-text-field
                                                                    outlined
                                                                    prepend-icon="mdi-coin-outline"
                                                                    dense
                                                                        type="number"
                                                                        v-model="item.paidprice"
                                                                        label="Price to Paid"
                                                                    ></v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm6>
                                                                </v-flex>
                                                                <v-flex xs12>
                                                                    <v-textarea
                                                                        v-model="item.description"
                                                                        outlined
                                                                        prepend-icon="mdi-card-text-outline"
                                                                        dense
                                                                        label="Details"
                                                                    ></v-textarea>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-container>
                                                    </v-expansion-panel-content>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>
                                            </div>
                                            <v-alert  v-else dense outlined type="info" >
                                                No Companies added yet .
                                            </v-alert>
                                        </v-flex>
                                        <v-flex xs12 >
                                            <v-dialog v-model="dialog2" width="80%" min-height="80%" persistent >
                                                <v-card > 
                                                    <v-container grid-list-md ref="fullmapss"  >
                                                        <v-layout row wrap>
                                                            <v-flex xs10 >
                                                                <div  style="height: 80vh; width: 100% ; " >
                                                                    <v-img  color="white" height="100%" width="100%"  :src="event.map " contain ></v-img>
                                                                </div>
                                                                <canvas id="imageCanvas" ref="imageCanvas"></canvas>
                                                            </v-flex>
                                                            <template>
                                                            </template>
                                                            <v-flex xs2 >
                                                                <v-btn @click="close_map()" icon right class="float-right mb-5" color="teal">
                                                                    <v-icon>mdi-close-circle</v-icon>
                                                                </v-btn>
                                                                <br><br>
                                                                <p class="subheading"> Select a map:</p>
                                                                <v-divider></v-divider>
                                                                <upload-btn
                                                                    block
                                                                    @file-update="file_changed"
                                                                    fixedWidth="300"
                                                                    title="Add Map"
                                                                    color="teal" 
                                                                    class="custom_button my-3 "                                                                       >
                                                                    <template slot="icon">
                                                                        <v-icon  right>mdi-content-save-edit</v-icon>
                                                                    </template>
                                                                </upload-btn>
                                                                <v-divider></v-divider>
                                                                <p class="subheading pt-3"> Place Company on the map:</p>
                                                                <v-divider></v-divider>
                                                                <v-container grid-list-md>
                                                                    <v-layout row wrap>
                                                                        <template v-if="event.map">
                                                                            <v-flex xs6 sm3 v-for="(item, index) in event.companies"
                                                                                :key="index" >
                                                                                    <div v-draggable="draggableValue" :data="item" @click="test(item)">
                                                                                        <v-avatar>
                                                                                        <img
                                                                                            :src="item.logo"
                                                                                            :alt="item.name"
                                                                                        >
                                                                                        </v-avatar>
                                                                                    </div>
                                                                            </v-flex>
                                                                        </template>
                                                                        <template v-else>
                                                                            <v-flex xs12>
                                                                                <v-alert type="info" outlined dense >
                                                                                    Add map first.
                                                                                </v-alert>
                                                                            </v-flex>
                                                                        </template>
                                                                    </v-layout>
                                                                </v-container>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-container>
                                                </v-card>
                                            </v-dialog>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-stepper-content>
                        </v-stepper-items>
                    </v-stepper>
                </v-flex>
                <v-flex xs6 sm3>
                    <v-card class="pa-3">
                        <p class="subheading pa-0 font-weight-bold">Event Overview</p>
                         <v-list two-line>
                        <v-list-item >
                            <v-list-item-content>
                            <v-list-item-title class="text-capitalize font-weight-bold primary--text " >{{event.name ? event.name:'Not set'}}</v-list-item-title>
                            <v-list-item-subtitle class="font-weight-bold">Name</v-list-item-subtitle>
                            </v-list-item-content>
                            <v-list-item-icon>
                            <v-icon>mdi-card-text-outline</v-icon>
                            </v-list-item-icon>
                        </v-list-item>
                        <v-list-item >
                            <v-list-item-content>
                            <v-list-item-title class="text-capitalize font-weight-bold primary--text" v-text="dateRangeText ? dateRangeText : 'Not Set' " ></v-list-item-title>
                            <v-list-item-subtitle class="font-weight-bold">Date Range</v-list-item-subtitle>
                            </v-list-item-content>
                            <v-list-item-icon>
                            <v-icon>mdi-calendar-outline</v-icon>
                            </v-list-item-icon>
                        </v-list-item>
                        <v-divider></v-divider>
                        <p class="subheading pa-0 font-weight-bold pt-3">Prices Overview</p>
                        <div v-if="event.prices.length">
                            <v-list-item v-for="items in event.prices " :key="items.id" dense  >
                                <v-list-item-content>
                                <v-list-item-title class="text-capitalize font-weight-bold" >{{items.price ? items.price : 'not Set'}}</v-list-item-title>
                                <v-list-item-subtitle>{{items.name}}</v-list-item-subtitle>
                                </v-list-item-content>
                                <v-list-item-icon>
                                <v-icon>mdi-currency-eur</v-icon>
                                </v-list-item-icon>
                            </v-list-item>
                        </div>
                        <v-alert type="info" outlined dense v-else >
                            No Prices added yet.
                        </v-alert>
                        <v-divider></v-divider>
                        <p class="subheading pa-0 font-weight-bold pt-3">Program Overview</p>
                        <div v-if="event.programs.length">
                            <v-list-item v-for="items in event.programs " :key="items.id" dense  >
                                <v-list-item-content>
                                <v-list-item-title class="text-capitalize font-weight-bold" >{{items.name ? items.name : 'add details'}}</v-list-item-title>
                                <v-list-item-subtitle>{{items.time}}</v-list-item-subtitle>
                                </v-list-item-content>
                                <v-list-item-icon>
                                <v-icon>mdi-clock-outline</v-icon>
                                </v-list-item-icon>
                            </v-list-item>
                        </div>
                        <v-alert type="info" outlined dense v-else >
                            No Program added yet.
                        </v-alert>
                         </v-list>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>
<script>
import UploadButton from 'vuetify-upload-button';
import { Draggable } from 'draggable-vue-directive' ;
export default {
    components: {
      'upload-btn': UploadButton,
    },
    directives: {
        Draggable,
    },
   data() {
        return {
        draggableValue: {
            onDragEnd: this.onPosChanged
        },
        width: 0,
        height: 0,
        x: 0,
        y: 0,
        e1:1 ,
        menu :'',
        dialog2:false,
        dialog:false,
        default_image:false,
        timepick:'',
        highlight:false ,
        img_preview:'',
        min_date : new Date().toJSON() ,
        event : {
            image:'',
            name:'',
            place:'',
            dates: [],
            to:'',
            map:'',
            fullmap:'',
            from:'',
            description:'',
            prices:[],
            programs:[],
            companies:[],
            presentation: {
                details: '',
                image: '',
                id: ''
            },
            logo:'',
            presentation_image:'',
            programme_image:'',
            tarifs_image:'',
            plan_image:'',
            restaurant_image:'',
            highlights_image:'',
        },
        categories:[],
        companies:[],
        select_categories: '',
        active: [],
    }
   },
    computed: {
        dateRangeText () {
            return this.event.dates.join(' ~ ')
        },
        
    },
    methods : {
        test(item){
            this.active = item ;
            console.log(this.active)
        },
         onPosChanged: function(positionDiff, absolutePosition, event) {
             setTimeout(() => {
                this.active.x =  absolutePosition.left 
                this.active.y =  absolutePosition.top 
              , 1000});
        },
        close_map(){
            this.dialog2 = !this.dialog2 ;
            let self = this;
            html2canvas(this.$refs.fullmapss).then(function (canvas) {
                var img = canvas.toDataURL("image/jpeg");
                self.event.fullmap = img ;
            });
        },
        onDragstop: function (x, y) {
            console.log(x,y)
        },
        open_map(){
            this.dialog2 = !this.dialog2 ;
        },
        submit(){
            this.$validator.validateAll().then(result => {
                if (result){
                    this.$root.$confirm('Are you sure you want to save ?').then((result) => {
                        if(result) {
                            axios.post('/events', this.event )
                            .then((response) =>  {
                                console.log(response.data)
                                this.$store.commit('setSnack', 'Event Saved !')
                                // this.$router.push('/event');

                            })
                        }
                    })
                }
            });
        },
        
        use_default(){
            this.default_image = !this.default_image ;
            this.event.image = ''
            this.event.logo = ''
            this.event.presentation_image = ''
            this.event.programme_image = ''
            this.event.tarifs_image = ''
            this.event.plan_image = ''
            this.event.restaurant_image = ''
            this.event.highlight = ''
        },
        
        image_preview(event) {
            let element = event.currentTarget
            this.img_preview =  element.getAttribute('data-image');
            this.dialog = true ;
        },

        get_categories() {
            axios.get('/categories', {})
            .then(response => {
                response.data.forEach(element => {
                    this.categories.push({'name' : element.person , 'id' : element.id , 'price':''})
                });
            });
        },

        get_companies() {
            axios.get('/companies', {})
            .then(response => {
                response.data.forEach(element => {
                    this.companies.push({'id' : element.id  ,'name' : element.name , 'paidprice' :'' , 'x':'' , 'y':'' , 'description': '', 'highlight':false , 'logo':element.logo ,'is_restaurant':false,'model': false })
                    console.log(this.companies)
                });
            });
        },

        select_category(e){
            console.log(e[0])
            e.value = '';
            // this.event.categories_selected.push(e);
        },
        file_changed(file) {
            console.log(file)
            if (file) {
                var reader = new FileReader();
                reader.onload = (e) => {
                    this.event.map = e.target.result ;
                }
                reader.readAsDataURL(file);
            }else {
               return
            }
        },
        
        add_programs() {
            let list = {'name' : '' , 'time': '', 'details':'','highlight':false ,'model': false }
            this.event.programs.push(list);
        },

        remove_program(index) {
            this.event.programs.splice(index,1);
        },

        remove_company(index) {
            this.event.companies.splice(index,1);
        },

        onFileChange(files , hint) {
            if (files) {
                
            var reader = new FileReader();
            reader.onload = (e) => {
                if(hint == 'present_image'){
                    this.event.presentation.image = e.target.result  ;
                }
                if(hint == 'event_image'){
                    this.event.image = e.target.result  ;
                }
                if(hint == 'event_logo'){
                    this.event.logo = e.target.result  ;
                }
                if(hint == 'presentation_image'){
                    this.event.presentation_image = e.target.result  ;
                }
                if(hint == 'programme_image'){
                    this.event.programme_image = e.target.result  ;
                }
                if(hint == 'tarifs_image'){
                    this.event.tarifs_image = e.target.result  ;
                }
                if(hint == 'plan_image'){
                    this.event.plan_image = e.target.result  ;
                }
                if(hint == 'restaurant_image'){
                    this.event.restaurant_image = e.target.result  ;
                }
                if(hint == 'highlights_image'){
                    this.event.highlights_image = e.target.result  ;
                }
            }
            reader.readAsDataURL(files);
            }else {
                return
            }

        },

        img_remove(event , hint){
            if(hint == 'present_image'){
                    this.event.presentation.image = '' ;
                }
                if(hint == 'event_image'){
                    this.event.image = ''  ;
                }
                if(hint == 'event_logo'){
                    this.event.logo = ''  ;
                }
                if(hint == 'presentation_image'){
                    this.event.presentation_image = '' ;
                }
                if(hint == 'programme_image'){
                    this.event.programme_image = ''  ;
                }
                if(hint == 'tarifs_image'){
                    this.event.tarifs_image = '' ;
                }
                if(hint == 'plan_image'){
                    this.event.plan_image = ''  ;
                }
                if(hint == 'restaurant_image'){
                    this.event.restaurant_image = '' ;
                }
                if(hint == 'highlights_image'){
                    this.event.highlights_image = ''  ;
                }
        },

    },

    created() {
        this.get_categories();
        this.get_companies();
    },
    
}
</script>