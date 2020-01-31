<template>
  <l-map style="height: 100%; width: 100%" :zoom="zoom" :center="center" :options="{zoomControl: false}" :crs="crs">
    <l-image-overlay
      :url="url"
      :bounds="bounds">
    </l-image-overlay>
    <l-marker
      v-for="star in stars"
      :lat-lng="star"
      :key="star.name">
      <l-popup :content="star.name"/>
    </l-marker>
  </l-map>
</template>
<script>
export default {
     data: (v,) => ({
        zoom:0,
        url: 'http://leafletjs.com/examples/crs-simple/uqm_map_full.png',
      bounds: [[-26.5, -25], [1021.5, 1023]],
      crs: L.CRS.Simple,
      stars: [
        { name: 'Sol', lng: 175.2, lat: 145.0 },
        { name: 'Mizar', lng: 41.6, lat: 130.1 },
        { name: 'Krueger-Z', lng: 13.4, lat: 56.5 },
        { name: 'Deneb', lng: 218.7, lat: 8.3 }
      ]
    }),
}
</script>