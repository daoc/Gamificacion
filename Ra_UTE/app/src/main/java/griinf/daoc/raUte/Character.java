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
    private float probability;

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
        probability = 0.05f;

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

    private float factor = 3f;
    public void move() {

        if(rnd.nextFloat() < probability) {
            vector.set(rnd.nextFloat() * factor - factor / 2, rnd.nextFloat() * factor - factor / 2, rnd.nextFloat() * factor - factor / 2);
            getCloser(vector, 50, 5);
        }
        getObj().translate(vector);
        System.out.println(getObj().getTransformedCenter());
    }

    private void getCloser(SimpleVector vector, float max, float step) {
        if(getObj().getTransformedCenter().x > max) {
            vector.x = -step;
        } else if(getObj().getTransformedCenter().x < -max) {
            vector.x = step;
        }
        if(getObj().getTransformedCenter().y > max) {
            vector.y = -step;
        } else if(getObj().getTransformedCenter().y < -max) {
            vector.y = step;
        }
        if(getObj().getTransformedCenter().z > max) {
            vector.z = -step;
        } else if(getObj().getTransformedCenter().z < -max) {
            vector.z = step;
        }
    }
}
