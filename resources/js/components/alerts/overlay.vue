<template>
	<div class="text-center">
		<v-overlay :value="$store.state.overlay" dark>
			<v-progress-circular indeterminate size="64" color="teal lighten-1"></v-progress-circular>
		</v-overlay>
	</div>
</template>