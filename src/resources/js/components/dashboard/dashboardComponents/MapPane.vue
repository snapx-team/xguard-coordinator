<template>
	<div class="relative">
		<transition enter-active-class="transition duration-500 ease-out transform"
					enter-class=" opacity-0 bg-blue-200"
					leave-active-class="transition duration-300 ease-in transform"
					leave-to-class="opacity-0 bg-blue-200">
			<div class="z-50 overflow-auto absolute inset-0 bg-gray-400 bg-opacity-50 flex"
				 v-if="isLoadingLocationPathData">
				<loading-animation :size="70" class="m-auto"></loading-animation>
			</div>
		</transition>

		<GmapMap name="google-maps" id="map" v-bind="mapPaneData.options" :key="mapPaneData.googleMapRefreshKey">

			<GmapMarker
				:position="mapPaneData.pathData.path[selectedPathPing]"
                icon="https://maps.google.com/mapfiles/ms/micons/green-dot.png">
            </GmapMarker>

			<GmapMarker
				:key="index"
				v-for="(m, index) in mapPaneData.jobSiteMarkers"
				:position="m.position"
				:icon="m.icon"
				:clickable="true"
				:label="m.label"
				@click="openWindow(m, index)">

            </GmapMarker>

			<GmapMarker
				:key="index"
				v-for="(m, index) in mapPaneData.pathData.stopsMarkers"
				:position="m.position"
				:icon="m.icon"
				:clickable="true"
				@click="openWindow(m, index)">

            </GmapMarker>

			<GmapCircle
				v-if="selectedStopMarker"
				:center="selectedStopMarker"
				:radius="distanceThreshold"
				:visible="true"
				:options="{fillColor:'blue',fillOpacity:0.1}">

            </GmapCircle>

			<GmapInfoWindow
				@closeclick="mapPaneData.window_open=false"
				:opened="mapPaneData.window_open"
				:position="mapPaneData.infoPosition"
				:options="mapPaneData.infoOptions">

            </GmapInfoWindow>

			<GmapPolyline v-bind:path.sync="mapPaneData.pathData.path"
						   v-bind:options="{ strokeColor:'#43f5ff'}">

            </GmapPolyline>

		</GmapMap>
	</div>
</template>
<script>
import LoadingAnimation from "../../global/LoadingAnimation"
import moment from "moment";

export default {
    inject: ["eventHub"],
    name: 'MapPane',
	components: {LoadingAnimation},
	props: {
		distanceThreshold: {},
		isLoadingLocationPathData: {},
		mapPaneData: {},
		selectedPathPing: {},
	},

    data() {
        return {
            selectedStopMarker: null,
        }
    },

    watch: {
        isLoadingLocationPathData: function (newVal) {
            if(newVal){
                this.selectedStopMarker = null;
            }
        }
    },

    created() {
        this.eventHub.$on("open-gmap-window", (data) => {
            this.openWindow(data.marker, data.index);
        });
    },

    beforeDestroy() {
        this.eventHub.$off('open-gmap-window');
    },

    methods: {
        getPosition: function (marker) {
            return {
                lat: marker.position.lat,
                lng: marker.position.lng
            };
        },

        openWindow(marker, index) {
            this.dataPaneIsVisible = true;
            this.mapPaneData.infoPosition = this.getPosition(marker);
            if (marker.type === 'jobSiteVisit') {
                this.mapPaneData.infoOptions.content =
                    '<div class="">' +
                    '<p class="font-semibold pb-2">' +
                    marker.name +
                    '</p>' +
                    '<p class="">' +
                    marker.address +
                    '</p>' +
                    '</div>';
            } else if (marker.type === 'stop') {
                this.selectedStopMarker = marker.position
                this.mapPaneData.infoOptions.content =
                    '<div class="">' +
                    '<p class="font-semibold pb-2">' +
                    'Stopped roughly ' + marker.totalTime + ' minutes' +
                    '</p>' +
                    '<p class="font-semibold pb-2">' +
                    'Between: ' + moment(marker.startTime).format('HH:mm') + 'h - ' + moment(marker.endTime).format('HH:mm') + 'h ' +
                    '</p>' +
                    '<p class="pb-2">' +
                    'Coordinates:' +
                    '</p>' +
                    '<p class="">' +
                    marker.coordinates +
                    '</p>' +
                    '</div>';
            }

            if (this.mapPaneData.currentMarkerIndex === index) {
                this.mapPaneData.window_open = !this.mapPaneData.window_open;
            } else {
                this.mapPaneData.window_open = true;
                this.mapPaneData.currentMarkerIndex = index;
            }
        },
    },
}
</script>
<style scoped>

#map {
	height: 600px;
	width: 100%;
	margin: 0 auto;
}

</style>
