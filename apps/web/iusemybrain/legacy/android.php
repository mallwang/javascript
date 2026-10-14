<?php $title="Android"; include 'header.php';?>
<script src="highlighter/prettify.js"></script>
<div align="center" class="programmierung">
	<br> <br>
	<div class="row">
		<div class="span3 bs-docs-sidebar">
		<br><br><br>
			<ul class="nav nav-tabs nav-stacked affix">
	    		<li class="active"><a href='#internetverbindung'>Internetverbindung prüfen</a></li>
	    		<li><a href='#gps'>GPS-Funktion nutzen</a></li>
	  		</ul>
		</div>
		<div class="span9">
			<!-- Global Bootstrap settings
        ================================================== -->
			<section id="internetverbindung">
			<br><br><br>
				<h2>Internetverbindung prüfen</h2>
				<p style="font-size: 16px;">Mithilfe dieser Funktion wird geprüft, ob die Internetverbindung aktiviert wurde und ob auch Daten gesendet bzw. empfangen werden können.</p>
<pre class="prettyprint">
// Benötigte Klassen importieren
import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;

// Prüfen, ob eine Internetverbindung besteht
public boolean isNetworkAvailable() {
	ConnectivityManager cm = (ConnectivityManager) 
			getSystemService(Context.CONNECTIVITY_SERVICE);
	NetworkInfo networkInfo = cm.getActiveNetworkInfo();
	// Wenn das Netzwerk aktiv ist UND eine Verbindung besteht
	if (networkInfo != null && networkInfo.isConnected()) {
		return true;
	}
	return false;
}
</pre>
			</section>


			<!-- Zweiter Punkt
        ================================================== -->
			<section id="gps">
				<br><br><br>
				<h2>GPS nutzen</h2>
				<p style="font-size: 16px;">Für das Nutzen der GPS-Funktion erstellen wir zunächst eine Klasse "GPSTracker"</p>
<pre class="prettyprint">
import android.app.AlertDialog;
import android.app.Service;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.os.IBinder;
import android.provider.Settings;
import android.util.Log;
 
public class GPSTracker extends Service implements LocationListener {
 
    private final Context mContext;
 
    // flag for GPS status
    boolean isGPSEnabled = false;
 
    // flag for network status
    boolean isNetworkEnabled = false;
 
    // flag for GPS status
    boolean canGetLocation = false;
 
    Location location; // location
    double latitude; // latitude
    double longitude; // longitude
 
    // The minimum distance to change Updates in meters
    private static final long MIN_DISTANCE_CHANGE_FOR_UPDATES = 10; // 10 meters
 
    // The minimum time between updates in milliseconds
    private static final long MIN_TIME_BW_UPDATES = 1000 * 60 * 1; // 1 minute
 
    // Declaring a Location Manager
    protected LocationManager locationManager;
 
    public GPSTracker(Context context) {
        this.mContext = context;
        getLocation();
    }
 
    public Location getLocation() {
        try {
            locationManager = (LocationManager) mContext
                    .getSystemService(LOCATION_SERVICE);
 
            // getting GPS status
            isGPSEnabled = locationManager
                    .isProviderEnabled(LocationManager.GPS_PROVIDER);
 
            // getting network status
            isNetworkEnabled = locationManager
                    .isProviderEnabled(LocationManager.NETWORK_PROVIDER);
 
            if (!isGPSEnabled && !isNetworkEnabled) {
                // no network provider is enabled
            } else {
                this.canGetLocation = true;
                // First get location from Network Provider
                if (isNetworkEnabled) {
                    locationManager.requestLocationUpdates(
                            LocationManager.NETWORK_PROVIDER,
                            MIN_TIME_BW_UPDATES,
                            MIN_DISTANCE_CHANGE_FOR_UPDATES, this);
                    Log.d("Network", "Network");
                    if (locationManager != null) {
                        location = locationManager
                                .getLastKnownLocation(LocationManager.NETWORK_PROVIDER);
                        if (location != null) {
                            latitude = location.getLatitude();
                            longitude = location.getLongitude();
                        }
                    }
                }
                // if GPS Enabled get lat/long using GPS Services
                if (isGPSEnabled) {
                    if (location == null) {
                        locationManager.requestLocationUpdates(
                                LocationManager.GPS_PROVIDER,
                                MIN_TIME_BW_UPDATES,
                                MIN_DISTANCE_CHANGE_FOR_UPDATES, this);
                        Log.d("GPS Enabled", "GPS Enabled");
                        if (locationManager != null) {
                            location = locationManager
                                    .getLastKnownLocation(LocationManager.GPS_PROVIDER);
                            if (location != null) {
                                latitude = location.getLatitude();
                                longitude = location.getLongitude();
                            }
                        }
                    }
                }
            }
 
        } catch (Exception e) {
            e.printStackTrace();
        }
 
        return location;
    }
     
    /**
     * Stop using GPS listener
     * Calling this function will stop using GPS in your app
     * */
    public void stopUsingGPS(){
        if(locationManager != null){
            locationManager.removeUpdates(GPSTracker.this);
        }       
    }
     
    /**
     * Function to get latitude
     * */
    public double getLatitude(){
        if(location != null){
            latitude = location.getLatitude();
        }
         
        // return latitude
        return latitude;
    }
     
    /**
     * Function to get longitude
     * */
    public double getLongitude(){
        if(location != null){
            longitude = location.getLongitude();
        }
         
        // return longitude
        return longitude;
    }
     
    /**
     * Function to check GPS/wifi enabled
     * @return boolean
     * */
    public boolean canGetLocation() {
        return this.canGetLocation;
    }
     
    /**
     * Function to show settings alert dialog
     * On pressing Settings button will lauch Settings Options
     * */
    public void showSettingsAlert(){
        AlertDialog.Builder alertDialog = new AlertDialog.Builder(mContext);
      
        // Setting Dialog Title
        alertDialog.setTitle("GPS ausgeschaltet");
  
        // Setting Dialog Message
        alertDialog.setMessage("Ihr GPS ist nicht aktiviert.\nBitte aktivieren Sie es.");
  
        // On pressing Settings button
        alertDialog.setPositiveButton("Aktivieren", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog,int which) {
                Intent intent = new Intent(Settings.ACTION_LOCATION_SOURCE_SETTINGS);
                mContext.startActivity(intent);
            }
        });
  
        // on pressing cancel button
        alertDialog.setNegativeButton("Abbruch", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int which) {
            dialog.cancel();
            }
        });
  
        // Showing Alert Message
        alertDialog.show();
    }
 
    @Override
    public void onLocationChanged(Location location) {
    }
 
    @Override
    public void onProviderDisabled(String provider) {
    }
 
    @Override
    public void onProviderEnabled(String provider) {
    }
 
    @Override
    public void onStatusChanged(String provider, int status, Bundle extras) {
    }
 
    @Override
    public IBinder onBind(Intent arg0) {
        return null;
    }
}
</pre>
			</section>
		</div>
	</div>
</div>
<script>prettyPrint();</script>
<?php include 'footer.php';?>