package com.utkarshlamba.offlinebling;

import android.app.FragmentManager;
import android.app.ProgressDialog;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.telephony.SmsMessage;
import android.util.Log;

/**
 * Created by utk on 16-01-23.
 */
public class SMSReceiver extends BroadcastReceiver {
    private String TAG = SMSReceiver.class.getSimpleName();
    private FragmentManager fm;
    private ProgressDialog pd;

    public SMSReceiver(FragmentManager fm, ProgressDialog pd) {
        this.fm = fm;
        this.pd=pd;
    }

    @Override
    public void onReceive(Context context, Intent intent) {

        // Get the data (SMS data) bound to intent
        Bundle bundle = intent.getExtras();

        SmsMessage[] msgs = null;

        String str = "";

        if (bundle != null) {
            // Retrieve the SMS Messages received
            Object[] pdus = (Object[]) bundle.get("pdus");
            msgs = new SmsMessage[pdus.length];

            // For every SMS message received
            for (int i=0; i < msgs.length; i++) {
                // Convert Object array
                msgs[i] = SmsMessage.createFromPdu((byte[]) pdus[i]);
                // Sender's phone number
                str += "SMS from " + msgs[i].getOriginatingAddress() + " : ";
                // Fetch the text message
                str += msgs[i].getMessageBody().toString();
                // Newline <img src="http://codetheory.in/wp-includes/images/smilies/simple-smile.png" alt=":-)" class="wp-smiley" style="height: 1em; max-height: 1em;">
                str += "\n";
            }

            // Display the entire SMS Message
            //progressDialog.close();
            pd.dismiss();
            fm.beginTransaction().replace(R.id.content_frame, new QueryResultsFragment(str)).commit();
        }
    }
}