package ute.griinf.problemon;

import android.content.Context;
import android.os.AsyncTask;
import android.widget.Toast;

import java.io.IOException;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;

public class PostRestCrud extends AsyncTask<String, String, String> {
    private final Context context;

    public PostRestCrud(Context context) {
        this.context = context;
    }

    /*
    Parámetros: url, data, mime (de data)
     */

    @Override
    protected String doInBackground(String... param) {
        if(param.length == 0) {
            return "No hay nada que ejecutar!";
        }
        try {
            URL url = new URL(param[0]);
            HttpURLConnection c = (HttpURLConnection) url.openConnection();
            c.setDoOutput(true);
            c.setRequestMethod("POST");
            if(param.length >= 3) {
                c.setRequestProperty("Content-Type", param[2]);
            }
            c.connect();
            if(param.length >= 2) {
                c.getOutputStream().write(param[1].getBytes());
            }
            String respMsg = c.getResponseMessage();
            c.disconnect();
            return respMsg;
        } catch (MalformedURLException e) {
            e.printStackTrace();
            return e.getMessage();
        } catch (IOException e) {
            e.printStackTrace();
            return e.getMessage();
        }
    }

    @Override
    protected void onPostExecute(String s) {
        Toast.makeText(context, "Resultado: " + s, Toast.LENGTH_LONG).show();
    }
}
