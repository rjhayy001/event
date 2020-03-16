<script>
import moment from 'moment';
moment.locale('fr');
export default {
    methods: {
        fulldate(date) {
            return moment(date).format('Do MMMM YYYY');
        }
    }
    
}
</script>