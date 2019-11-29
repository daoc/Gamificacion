package griinf.daoc.raUte;

import android.content.Context;
import android.support.annotation.Nullable;

import com.threed.jpct.Loader;
import com.threed.jpct.Matrix;
import com.threed.jpct.Object3D;
import com.threed.jpct.SimpleVector;
import com.threed.jpct.Texture;
import com.threed.jpct.TextureManager;
import com.threed.jpct.util.BitmapHelper;

import java.io.InputStream;
import java.util.Random;

public class Character {
    public static enum Type3D {
        _3DS,
        _OBJ
    }
    private Context context;
    private Object3D obj;
    Random rnd = new Random();
    private SimpleVector vector;

    private float probability = 0.1f;
    private float factor = 0.5f;
    private float max_distance = 80f;
    private float min_distance = 20f;
    private float step_distance = 1f;

    public Character(Context context, Type3D type, int modelRes, float scale, @Nullable Integer materialRes, @Nullable String texture) {
        this.context = context;
        init(type, modelRes, scale, materialRes, texture);
    }

    public Object3D getObj() {
        return obj;
    }

    private void init(Type3D type, int modelRes, float scale, Integer materialRes, String texture) {
        vector = new SimpleVector();
        //vector.set(rnd.nextFloat(), rnd.nextFloat(), rnd.nextFloat());

        InputStream modelStream = context.getResources().openRawResource(modelRes);
        InputStream materialStream = null;
        if(type == Type3D._3DS) {
            obj = Object3D.mergeAll(Loader.load3DS(modelStream, scale));
        } else if (type == Type3D._OBJ) {
            if(materialRes != null) {
                materialStream = context.getResources().openRawResource(materialRes);
            }
            obj = Object3D.mergeAll(Loader.loadOBJ(modelStream, materialStream, scale));
        }
        if(texture != null) {
            obj.setTexture(texture);
        }
        obj.build();
    }

    public void turn(float side, float up) {
        getObj().rotateY(side);
        getObj().rotateX(up);
    }

    public void move() {

//        if(rnd.nextFloat() < probability) {
//            vector.set(0, 0, 1);
//            //vector.set(rnd.nextFloat() * factor - factor / 2, rnd.nextFloat() * factor - factor / 2, rnd.nextFloat() * factor - factor / 2);
//        }
        if(rnd.nextFloat() < probability) {
            float x = rnd.nextFloat() * factor;
            float y = rnd.nextFloat() * factor;
            float z = rnd.nextFloat() * factor;

            if(rnd.nextBoolean()) x *= -1;
            if(rnd.nextBoolean()) y *= -1;
            if(rnd.nextBoolean()) z *= -1;

            vector.set(x, y, z);
            //vector.set(rnd.nextFloat() * factor - factor / 2, rnd.nextFloat() * factor - factor / 2, rnd.nextFloat() * factor - factor / 2);
        }
        //getFarther(vector, min_distance, step_distance);
        //getCloser(vector, max_distance, step_distance);



        getInRange(vector, max_distance, min_distance, step_distance);
        getObj().translate(vector);
        System.out.println(getObj().getTransformedCenter());
    }

    private void getInRange(SimpleVector motion, float max, float min, float step) {
        //SimpleVector distVect=getObj().getTransformedCenter().calcSub(cam.getTransformedCenter());
        //motion.distance()
        SimpleVector goBack;
        float distance = getObj().getTransformedCenter().distance(SimpleVector.ORIGIN);
        System.out.println("Distance: " + distance);

        if(distance > max_distance) {
            goBack = getObj().getTransformedCenter();//.normalize();
            goBack.scalarMul(-1);
            motion.set(goBack);
        }

//        float x = getObj().getTransformedCenter().x;
//        float y = getObj().getTransformedCenter().y;
//        float z = getObj().getTransformedCenter().z;
//
//        if(Math.abs(z) > max) {
//            vector.z = (z < 0) ? step : -step;
//        }
//        if(Math.abs(z) < min) {
//            vector.z = (z > 0) ? step : -step;
//        }

    }

    private void getCloser(SimpleVector vector, float max, float step) {
        float x = getObj().getTransformedCenter().x;
        float y = getObj().getTransformedCenter().y;
        float z = getObj().getTransformedCenter().z;

//        if(Math.abs(x) > max) {
//            vector.x = (x < 0) ? step : -step;
//        }
//        if(Math.abs(y) > max) {
//            vector.y = (y < 0) ? step : -step;
//        }
        if(Math.abs(z) > max) {
            vector.z = (z < 0) ? step : -step;
        }

//        if(y > max) {
//            vector.y = -step;
//        } else if(y < -max) {
//            vector.y = step;
//        }
//        if(z > max) {
//            vector.z = -step;
//        } else if(z < -max) {
//            vector.z = step;
//        }
    }

    private void getFarther(SimpleVector vector, float min, float step) {
        float x = getObj().getTransformedCenter().x;
        float y = getObj().getTransformedCenter().y;
        float z = getObj().getTransformedCenter().z;

//        if(Math.abs(x) < min) {
//            vector.x = (x < 0) ? -step : step;
//        }
//        if(Math.abs(y) > min) {
//            vector.y = (y < 0) ? -step : step;
//        }
        if(Math.abs(z) > min) {
            vector.z = (z < 0) ? -step : step;
        }

//        if(x < min) {
//            vector.x = step;
//        } else if(getObj().getTransformedCenter().x > -min) {
//            vector.x = -step;
//        }
//        if(getObj().getTransformedCenter().y < min) {
//            vector.y = step;
//        } else if(getObj().getTransformedCenter().y > -min) {
//            vector.y = -step;
//        }
//        if(getObj().getTransformedCenter().z < min) {
//            vector.z = step;
//        } else if(getObj().getTransformedCenter().z > -min) {
//            vector.z = -step;
//        }
    }

}
