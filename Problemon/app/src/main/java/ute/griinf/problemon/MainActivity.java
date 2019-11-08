package ute.griinf.problemon;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.view.View;

import org.json.JSONException;
import org.json.JSONObject;

import gl.Color;
import gl.GL1Renderer;
import gl.GLFactory;
import system.ArActivity;
import system.DefaultARSetup;
import worldData.World;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
    }

    public void doLogin(View v) {
        String url = "http://140.238.185.44:3000/api/Users/login";
        JSONObject json = new JSONObject();
        try {
            json.put("username", "admin");
            json.put("password", "admin");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        String mime = "application/json";
        new PostRestCrud(this).execute(url, json.toString(), mime);
    }

//    public void iraRa(View v) {
//        Intent intent = getPackageManager().getLaunchIntentForPackage("com.UTE.Tesis");
//        intent.putExtra("nombre", "admin");
//        startActivity(intent);
//    }

    public void droidArDemo(View v) {
        ArActivity.startWithSetup(MainActivity.this, new DefaultARSetup() {
            @Override
            public void addObjectsTo(GL1Renderer renderer, World world, GLFactory objectFactory) {
                Bitmap icon = BitmapFactory.decodeResource(getResources(), R.mipmap.ic_launcher);

                //world.add(objectFactory.newHexGroupTest(null));
                world.add(objectFactory.newCube(Color.green()));
                //world.add(objectFactory.newTexturedSquare("uno", BitmapFactory.decodeResource(getResources(), R.drawable.texture)));
            }
        });
    }

}
